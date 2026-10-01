<?= $this->include('templates/header', ['title' => 'Historique des actions', 'css' => 'accueiladmin']) ?>
  <section class="admin-page">
    <div class="container">
  <div class="page-heading">
        <h1>Historique des actions</h1>
        <a class="add-link" href="/docinterne/historiques/create">Ajouter un historique</a>
    </div>
    
    <?php if (empty($historiques)): ?>
        <p class="empty-state">Aucun historique pour le moment.</p>
    <?php else: ?>
        <div class="user-list">
            <?php foreach ($historiques as $historique): ?>
                <div class="user-row">
                    <div>
                        <strong class="user-name"><?= esc($historique['utilisateur_nom']) ?></strong>
                        <div>Adresse e-mail : <?= esc($historique['utilisateur_email']) ?></div>
                        <div>Document : <?= esc($historique['document_titre']) ?></div>
                        <div>Action : <?= esc($historique['action']) ?></div>
                        <div>Détails : <?= esc($historique['details']) ?></div>
                        <div>Date de création : <?= esc($historique['created_at']) ?></div>
                    </div>

                    <div class="user-actions">
                        <a class="edit-link" href="/docinterne/utilisateurs/edit/<?= $historique['user_id'] ?>">Modifier</a>
                        <form class="delete-form" action="/docinterne/utilisateurs/delete/<?= $historique['user_id'] ?>" method="post">
                            <?= csrf_field() ?>
                            <button
                                class="delete-button"
                                onclick="return confirm('Voulez-vous vraiment supprimer cet historique ?')"
                                type="submit">Supprimer</button>
                                
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
    <?php endif; ?>
                </div>
              </section>

     <section class="experience" id="experience">
    <div class="container">
      <div class="reveal">
        <span class="section-label"><span class="dot"></span> Experience</span>
        <h2 class="section-title">Where I've buzzed around.</h2>
        <p class="section-desc">From agency life to going independent — here's the path that brought me here.</p>
      </div>
      <div class="exp-marquee">
        
      </div>
      <div class="exp-grid">
        <div class="exp-card reveal">
          <div class="exp-card-img">
            <img src="images/creative-workspace.jpg" alt="Creative workspace">
            <span class="exp-card-duration">1+ year</span>
          </div>
          <div class="exp-card-body">
            <span class="exp-card-period">2024 – Present</span>
            <h3 class="exp-card-role">Freelance Designer & Developer</h3>
            <p class="exp-card-company">Self-Employed — Hustle Bee Studio</p>
            <p class="exp-card-desc">Running my own creative studio, working directly with startups and small businesses. I handle everything from branding strategy to full website builds, keeping things personal and nimble.</p>
            <div class="exp-tags">
              <span class="exp-tag">Branding</span><span class="exp-tag">Web Design</span>
              <span class="exp-tag">React</span><span class="exp-tag">Figma</span>
            </div>
          </div>
        </div>
        <div class="exp-card reveal reveal-delay-1">
          <div class="exp-card-img">
            <img src="images/digital-agency.jpg" alt="Digital agency">
            <span class="exp-card-duration">1 year</span>
          </div>
          <div class="exp-card-body">
            <span class="exp-card-period">2023 – 2024</span>
            <h3 class="exp-card-role">Junior Frontend Developer</h3>
            <p class="exp-card-company">Pixel & Co. Digital Agency</p>
            <p class="exp-card-desc">Built responsive websites and interactive prototypes for clients in retail, hospitality, and tech. Learned how great agencies ship at scale while keeping quality high.</p>
            <div class="exp-tags">
              <span class="exp-tag">HTML/CSS</span><span class="exp-tag">JavaScript</span>
              <span class="exp-tag">WordPress</span><span class="exp-tag">Agile</span>
            </div>
          </div>
        </div>
        <div class="exp-card reveal reveal-delay-2">
          <div class="exp-card-img">
            <img src="images/creative-studio.jpg" alt="Creative studio">
            <span class="exp-card-duration">1 year</span>
          </div>
          <div class="exp-card-body">
            <span class="exp-card-period">2022 – 2023</span>
            <h3 class="exp-card-role">Design Intern</h3>
            <p class="exp-card-company">Hive Creative Studio</p>
            <p class="exp-card-desc">Assisted the senior design team with brand refresh projects, social media assets, and pitch deck design. Got comfortable with fast-paced creative work under tight deadlines.</p>
            <div class="exp-tags">
              <span class="exp-tag">Figma</span><span class="exp-tag">Illustrator</span>
              <span class="exp-tag">Presentation Design</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
    
<?= $this->include('templates/footer') ?>