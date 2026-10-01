
CREATE DATABASE docinterne;

CREATE ROLE docinterne
LOGIN
PASSWORD 'docinterne';

GRANT CONNECT ON DATABASE docinterne TO docinterne;

\connect docinterne

GRANT USAGE ON SCHEMA public TO docinterne;

GRANT SELECT, INSERT, UPDATE, DELETE
ON utilisateurs, documents
TO docinterne;

GRANT USAGE, SELECT
ON ALL SEQUENCES IN SCHEMA public
TO docinterne;

\connect docinterne docinterne 
-- Extension nécessaire si vous voulez gérer le hachage en base (optionnel)
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- 1. Fonction automatique pour updated_at
CREATE OR REPLACE FUNCTION trigger_set_timestamp()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- 2. Départements
CREATE TABLE departements (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO departements (nom) VALUES 
('Informatique'), ('Comptabilité'), ('Ressources humaines'), ('Commercial');


CREATE TABLE utilisateurs (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    matricule VARCHAR(100) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, 
    departement_id INT REFERENCES departements(id) ON DELETE SET NULL,
    role VARCHAR(50) DEFAULT 'user',
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_utilisateurs_dept ON utilisateurs(departement_id);

insert into utilisateurs (matricule, nom, email, password, departement_id, role) values 
('admin', 'Administrateur', 'admin@docinterne.fr', 'hashed_password', 1, 'admin');

insert into utilisateurs (matricule, nom, email, password, departement_id, role) values 
('user001', 'Utilisateur', 'user@docinterne.fr', 'user001', 1, 'user');

insert into utilisateurs (matricule, nom, email, password, departement_id, role) values 
('user002', 'Jean', 'jean@docinterne.fr', 'jean', 1, 'user');

CREATE TABLE dossiers (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    parent_id INT REFERENCES dossiers(id) ON DELETE CASCADE,
    departement_id INT REFERENCES departements(id) ON DELETE CASCADE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_dossiers_parent ON dossiers(parent_id);
CREATE INDEX idx_dossiers_dept ON dossiers(departement_id);


INSERT INTO dossiers (nom, parent_id, departement_id)
VALUES
-- Dossiers racines
('Informatique', NULL, 1),
('Comptabilité', NULL, 2),
('Ressources humaines', NULL, 3),
('Commercial', NULL, 4);
-- Dossiers enfants informatique
INSERT INTO dossiers (nom, parent_id, departement_id)
VALUES
('Projets', 1, 1),
('Documentation technique', 1, 1),
('Maintenance', 1, 1),
('Réseau et infrastructure', 1, 1),
('Sécurité informatique', 1, 1);

-- Dossiers enfants comptabilité
INSERT INTO dossiers (nom, parent_id, departement_id)
VALUES
('Factures', 2, 2),
('Budgets', 2, 2),
('Salaires', 2, 2),
('Rapports financiers', 2, 2),
('Déclarations fiscales', 2, 2);

-- Dossiers enfants ressources humaines
INSERT INTO dossiers (nom, parent_id, departement_id)
VALUES
('Contrats', 3, 3),
('Congés', 3, 3),
('Recrutement', 3, 3),
('Formations', 3, 3),
('Évaluations', 3, 3);

-- Dossiers enfants commercial
INSERT INTO dossiers (nom, parent_id, departement_id)
VALUES
('Clients', 4, 4),
('Prospects', 4, 4),
('Devis', 4, 4),
('Contrats commerciaux', 4, 4),
('Rapports commerciaux', 4, 4);


CREATE TABLE documents (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    chemin_fichier VARCHAR(255) NOT NULL,
    type_fichier VARCHAR(50) NOT NULL,
    taille_fichier INT NOT NULL,
    dossier_id INT REFERENCES dossiers(id) ON DELETE CASCADE,
    created_by INT REFERENCES utilisateurs(id) ON DELETE SET NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_documents_dossier ON documents(dossier_id);
CREATE INDEX idx_documents_creator ON documents(created_by);
CREATE INDEX idx_documents_deleted ON documents(is_deleted) WHERE is_deleted = FALSE;

INSERT INTO documents
(titre, chemin_fichier, type_fichier, taille_fichier, dossier_id, created_by)
VALUES
('Rapport projet DocInterne',
 'uploads/documents/rapport_docinterne.pdf',
 'PDF',
 245760,
 5,
 1),

('Documentation technique',
 'uploads/documents/documentation_technique.pdf',
 'PDF',
 524288,
 6,
 1),

('Contrat de travail Jean',
 'uploads/documents/contrat_jean.pdf',
 'PDF',
 184320,
 15,
 1),

('Demande de congé',
 'uploads/documents/demande_conge.pdf',
 'PDF',
 98304,
 16,
 1);


CREATE TABLE document_permissions (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    document_id INT REFERENCES documents(id) ON DELETE CASCADE,
    departement_id INT REFERENCES departements(id) ON DELETE CASCADE,
    can_view BOOLEAN DEFAULT TRUE,
    can_edit BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    UNIQUE(document_id, departement_id)
);
CREATE INDEX idx_permissions_dept ON document_permissions(departement_id);

insert into document_permissions (document_id, departement_id, can_view, can_edit, can_delete) values
(13, 1, TRUE, TRUE, TRUE),
(13, 2,TRUE, TRUE, FALSE),
(13, 3,TRUE, TRUE, FALSE),
(13, 4,TRUE, TRUE, FALSE);


CREATE TABLE user_document_permissions (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    document_id INT NOT NULL REFERENCES documents(id) ON DELETE CASCADE,
    user_id INT NOT NULL REFERENCES utilisateurs(id) ON DELETE CASCADE,
    can_view BOOLEAN DEFAULT TRUE,
    can_edit BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    UNIQUE(document_id, user_id)
);

insert into user_document_permissions (document_id, user_id, can_view, can_edit, can_delete) values
(13, 3, TRUE, TRUE, FALSE);

CREATE TABLE historique_actions (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id INT REFERENCES utilisateurs(id) ON DELETE SET NULL,
    document_id INT REFERENCES documents(id) ON DELETE CASCADE,
    action VARCHAR(50) NOT NULL, 
    details TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_historique_user ON historique_actions(user_id);
CREATE INDEX idx_historique_doc ON historique_actions(document_id);

insert into historique_actions (user_id, document_id, action, details) values
(1, 13, 'Création', 'Document créé par administrateur'),
(1, 14, 'Création', 'Document créé par administrateur'),
(1, 15, 'Création', 'Document créé par administrateur'),
(1, 16, 'Création', 'Document créé par administrateur');

//CREATION DE VUE 
--historique des documents avec les informations de l'utilisateur et du document
CREATE VIEW V_historique_documents AS
SELECT 
    h.id AS historique_id,
    h.user_id,
    u.nom AS utilisateur_nom,
    u.email AS utilisateur_email,
    h.document_id,
    d.titre AS document_titre,
    h.action,
    h.details,
    h.created_at
FROM historique_actions h
JOIN utilisateurs u ON h.user_id = u.id 
JOIN documents d ON h.document_id = d.id;


--liste dossier par rapport a son departement 
CREATE OR REPLACE VIEW v_dossiers_departement AS
SELECT
    dt.id AS departement_id,
    dt.nom AS departement_nom,
    d.id AS dossier_id,
    d.nom AS dossier_nom,
    d.parent_id AS dossier_parent_id,
    doc.id AS document_id,
    doc.titre AS document_titre,
    doc.chemin_fichier AS document_chemin_fichier
FROM departements dt
JOIN dossiers d
    ON dt.id = d.departement_id
LEFT JOIN documents doc
    ON d.id = doc.dossier_id
   AND doc.is_deleted = FALSE;

--document avec les permissions par rapport au departement
CREATE OR REPLACE VIEW v_documents_detail_permission AS
SELECT
    vdep.departement_id,
    vdep.departement_nom,
    vdep.dossier_id,
    vdep.dossier_nom,
    vdep.dossier_parent_id,
    vdep.document_id,
    vdep.document_titre,
    vdep.document_chemin_fichier,
    COALESCE(docp.can_view, FALSE) AS can_view,
    COALESCE(docp.can_edit, FALSE) AS can_edit,
    COALESCE(docp.can_delete, FALSE) AS can_delete
FROM v_dossiers_departement vdep
LEFT JOIN document_permissions docp
    ON docp.document_id = vdep.document_id
   AND docp.departement_id = vdep.departement_id;


   -- document avec les permissions par rapport au departement pour chaque utilisateur
CREATE OR REPLACE VIEW v_documents_user_permission AS
SELECT
    vdep.*,udp.user_id,udp.can_view, udp.can_edit, udp.can_delete
FROM v_dossiers_departement AS vdep
LEFT JOIN user_document_permissions AS udp
  ON udp.document_id = vdep.document_id;

//UPDATE COMMANDE  
ALTER TABLE document_permissions RENAME COLUMN department_id TO departement_id;

SELECT
                vdep.*,
                udp.user_id,
                COALESCE(udp.can_view, FALSE) AS can_view,
                COALESCE(udp.can_edit, FALSE) AS can_edit,
                COALESCE(udp.can_delete, FALSE) AS can_delete
            FROM v_dossiers_departement AS vdep
            LEFT JOIN user_document_permissions AS udp
                ON udp.document_id = vdep.document_id
            AND udp.user_id = 3
            WHERE vdep.dossier_id = 5
            AND vdep.departement_id = 1;