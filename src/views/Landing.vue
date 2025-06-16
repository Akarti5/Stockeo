<template>
    <div class="landing-page">
      <!-- Header -->
      <header class="header">
  <nav class="nav container">
    <div class="logo">
      <img src="@/assets/Stockeo.png" alt="Stockeo" class="logo-img" />
      Stockeo
    </div>
    <ul class="nav-links">
      <li><a href="#" @click="currentPage = 'home'" :class="{ active: currentPage === 'home' }">Accueil</a></li>
      <li><a href="#" @click="currentPage = 'about'" :class="{ active: currentPage === 'about' }">À propos</a></li>
    </ul>
    
    <!-- Affichage conditionnel selon l'état de connexion -->
    <div v-if="isLoggedIn" class="user-section">
      <span class="user-greeting">Salut, {{ currentUser.nom }}!</span>
      <button @click="handleLogout" class="logout-btn">Se déconnecter</button>
    </div>
    <button v-else @click="openLoginModal" class="get-started-btn">Se connecter</button>
  </nav>
</header>
  
      <main class="main-content">
        <!-- Home Section -->
        <section v-if="currentPage === 'home'" class="home-section">
          <div class="floating-elements">
            <div class="floating-box"></div>
            <div class="floating-box"></div>
            <div class="floating-box"></div>
            <div class="floating-box"></div>
          </div>
          <div class="container">
            <div class="hero-content">
              <h1 class="hero-title">Bienvenue sur Stockeo</h1>
              <p class="hero-subtitle">Stockeo vous aide à gérer vos produits, quantités et mouvements de stock de manière simple et intuitive.</p>
              <div class="cta-buttons">
  <button @click="handleDashboardAccess" class="cta-primary">Commencer</button>
  <button @click="currentPage = 'about'" class="cta-secondary">En savoir plus</button>
</div>
            </div>
          </div>
        </section>
  
        <!-- About Section -->
        <section v-if="currentPage === 'about'" class="about-section">
          <div class="container">
            <h2 class="about-title">À propos de Stockeo</h2>
            <p class="about-description">
              Stockeo est une solution moderne de gestion de stock conçue pour simplifier la vie des entreprises. 
              Notre plateforme intuitive vous permet de suivre vos produits, gérer vos quantités et optimiser vos mouvements de stock en temps réel.
            </p>
            
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-number">{{ animatedUsers }}</div>
                <div class="stat-label">Utilisateurs actifs</div>
              </div>
              <div class="stat-card">
                <div class="stat-number">{{ animatedExperience }}</div>
                <div class="stat-label">Années d'expérience</div>
              </div>
            </div>
          </div>
        </section>
      </main>

      <!-- Login Modal -->
      <div v-if="showLoginModal" class="modal-overlay" @click="closeLoginModal">
        <div class="modal-content" @click.stop>
          <div class="modal-floating-elements">
            <div class="modal-floating-box"></div>
            <div class="modal-floating-box"></div>
            <div class="modal-floating-box"></div>
          </div>
          
          <button @click="closeLoginModal" class="close-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>

          <div class="modal-header">
            <div class="modal-logo">
              <img src="@/assets/Stockeo.png" alt="Stockeo" class="modal-logo-img" />
              <span>Stockeo</span>
            </div>
            <h2 class="modal-title">{{ isLoginMode ? 'Connexion' : 'Créer un compte' }}</h2>
            <p class="modal-subtitle">
              {{ isLoginMode ? 'Connectez-vous à votre compte' : 'Rejoignez notre communauté' }}
            </p>
          </div>

          <form @submit.prevent="handleSubmit" class="login-form">
            <div v-if="!isLoginMode" class="form-group">
              <label for="name" class="form-label">Nom complet</label>
              <input 
                type="text" 
                id="name" 
                v-model="form.name"
                class="form-input" 
                placeholder="Entrez votre nom complet"
                required
              />
            </div>

            <div class="form-group">
              <label for="email" class="form-label">Email</label>
              <input 
                type="email" 
                id="email" 
                v-model="form.email"
                class="form-input" 
                placeholder="Entrez votre email"
                required
              />
            </div>

            <div class="form-group">
              <label for="password" class="form-label">Mot de passe</label>
              <input 
                type="password" 
                id="password" 
                v-model="form.password"
                class="form-input" 
                placeholder="Entrez votre mot de passe"
                required
              />
            </div>

            <div v-if="!isLoginMode" class="form-group">
              <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
              <input 
                type="password" 
                id="confirmPassword" 
                v-model="form.confirmPassword"
                class="form-input" 
                placeholder="Confirmez votre mot de passe"
                required
              />
            </div>
            <!-- Dans votre formulaire, ajoutez : -->
<div v-if="message" class="message" :class="{ 'success': message.includes('succès'), 'error': !message.includes('succès') }">
  {{ message }}
</div>

<button type="submit" class="submit-btn" :disabled="loading">
  <span v-if="loading">⏳ Traitement...</span>
  <span v-else>{{ isLoginMode ? 'Se connecter' : 'Créer le compte' }}</span>
</button>

        
          </form>

          <div class="modal-footer">
            <div v-if="isLoginMode" class="forgot-password">
              <a href="#" @click="showForgotPassword" class="forgot-link">Mot de passe oublié ?</a>
            </div>
            
            <div class="switch-mode">
              <span>{{ isLoginMode ? 'Pas encore de compte ?' : 'Déjà un compte ?' }}</span>
              <button @click="toggleMode" class="switch-btn">
                {{ isLoginMode ? 'Créer un compte' : 'Se connecter' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Forgot Password Modal -->
      <div v-if="showForgotModal" class="modal-overlay" @click="closeForgotModal">
        <div class="modal-content forgot-modal" @click.stop>
          <button @click="closeForgotModal" class="close-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>

          <div class="modal-header">
            <h2 class="modal-title">Mot de passe oublié</h2>
            <p class="modal-subtitle">Entrez votre email pour recevoir un lien de réinitialisation</p>
          </div>

          <form @submit.prevent="handleForgotPassword" class="login-form">
            <div class="form-group">
              <label for="forgotEmail" class="form-label">Email</label>
              <input 
                type="email" 
                id="forgotEmail" 
                v-model="forgotEmail"
                class="form-input" 
                placeholder="Entrez votre email"
                required
              />
            </div>

            <button type="submit" class="submit-btn">
              Envoyer le lien
            </button>
          </form>

          <div class="modal-footer">
            <div class="switch-mode">
              <button @click="backToLogin" class="switch-btn">
                ← Retour à la connexion
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { registerUser, loginUser, logoutUser } from '@/API/auth.js'

  export default {
    name: 'Landing',
    data() {
      return {
        currentPage: 'home',
        animatedUsers: 0,
        animatedExperience: 0,
        showLoginModal: false,
        showForgotModal: false,
        isLoginMode: true,
        form: {
          name: '',
          email: '',
          password: '',
          confirmPassword: ''
        },
        forgotEmail: '',
        loading: false,
        message: '',
        // Nouvel état pour l'authentification
        isLoggedIn: false,
        currentUser: null
      }
    },
    watch: {
      currentPage(newPage) {
        if (newPage === 'about') {
          this.$nextTick(() => {
            this.animateNumbers()
          })
        }
      }
    },
    methods: {
        handleDashboardAccess() {
  if (this.isLoggedIn) {
    // Utilisateur connecté, rediriger vers le dashboard
    this.$router.push('/dashboard');
  } else {
    // Utilisateur non connecté, ouvrir le modal de connexion
    this.openLoginModal();
  }
},
      // Vos méthodes existantes...
      animateNumbers() {
        this.animateValue('animatedUsers', 0, 20000, 2000, '+', 'k')
        this.animateValue('animatedExperience', 0, 3, 1500, '', ' ans')
      },
      
      animateValue(property, start, end, duration, prefix = '', suffix = '') {
        const startTime = performance.now()
        const animate = (currentTime) => {
          const elapsed = currentTime - startTime
          const progress = Math.min(elapsed / duration, 1)
          const easeOut = 1 - Math.pow(1 - progress, 3)
          let currentValue = Math.floor(start + (end - start) * easeOut)
          
          if (property === 'animatedUsers') {
            if (currentValue >= 20000) {
              this[property] = prefix + '20k'
            } else if (currentValue >= 1000) {
              this[property] = prefix + Math.floor(currentValue / 1000) + 'k'
            } else {
              this[property] = prefix + currentValue + suffix
            }
          } else {
            this[property] = prefix + currentValue + suffix
          }
          
          if (progress < 1) {
            requestAnimationFrame(animate)
          }
        }
        requestAnimationFrame(animate)
      },

      openLoginModal() {
        this.showLoginModal = true
        document.body.style.overflow = 'hidden'
      },

      closeLoginModal() {
        this.showLoginModal = false
        document.body.style.overflow = 'auto'
        this.resetForm()
      },

      toggleMode() {
        this.isLoginMode = !this.isLoginMode
        this.resetForm()
      },

      resetForm() {
        this.form = {
          name: '',
          email: '',
          password: '',
          confirmPassword: ''
        }
        this.message = ''
      },

      // Méthode handleSubmit mise à jour
      async handleSubmit() {
        this.loading = true
        this.message = ''

        try {
          if (this.isLoginMode) {
            // Connexion
            const result = await loginUser({
              email: this.form.email,
              mot_de_passe: this.form.password
            })

            if (result.success && result.data.success) {
              this.message = 'Connexion réussie !'
              
              // Mettre à jour l'état de connexion
              this.isLoggedIn = true
              this.currentUser = result.data.user
              
              // Stocker dans localStorage
              localStorage.setItem('user', JSON.stringify(result.data.user))
              localStorage.setItem('isLoggedIn', 'true')
              
              setTimeout(() => {
                this.closeLoginModal()
              }, 1500)
            } else {
              this.message = result.data?.message || 'Erreur lors de la connexion'
            }
          } else {
            // Inscription
            if (this.form.password !== this.form.confirmPassword) {
              this.message = 'Les mots de passe ne correspondent pas'
              return
            }

            if (this.form.password.length < 6) {
              this.message = 'Le mot de passe doit contenir au moins 6 caractères'
              return
            }

            const result = await registerUser({
              nom: this.form.name,
              email: this.form.email,
              mot_de_passe: this.form.password
            })

            if (result.success && result.data.success) {
              this.message = 'Compte créé avec succès !'
              setTimeout(() => {
                this.isLoginMode = true
                this.resetForm()
              }, 2000)
            } else {
              this.message = result.data?.message || 'Erreur lors de la création du compte'
            }
          }
        } catch (error) {
          console.error('Erreur:', error)
          this.message = 'Erreur de connexion au serveur'
        } finally {
          this.loading = false
        }
      },

      // Nouvelle méthode pour la déconnexion
      async handleLogout() {
        try {
          const result = await logoutUser()
          
          // Nettoyer l'état local
          this.isLoggedIn = false
          this.currentUser = null
          
          // Nettoyer localStorage
          localStorage.removeItem('user')
          localStorage.removeItem('isLoggedIn')
          
          console.log('Déconnexion réussie')
        } catch (error) {
          console.error('Erreur lors de la déconnexion:', error)
        }
      },

      // Méthode pour vérifier l'état de connexion au chargement
      checkAuthState() {
        const savedUser = localStorage.getItem('user')
        const isLoggedIn = localStorage.getItem('isLoggedIn')
        
        if (savedUser && isLoggedIn === 'true') {
          this.isLoggedIn = true
          this.currentUser = JSON.parse(savedUser)
        }
      },

      showForgotPassword() {
        this.showForgotModal = true
        this.showLoginModal = false
      },

      closeForgotModal() {
        this.showForgotModal = false
        this.forgotEmail = ''
        document.body.style.overflow = 'auto'
      },

      backToLogin() {
        this.showForgotModal = false
        this.showLoginModal = true
        this.forgotEmail = ''
      },

      handleForgotPassword() {
        console.log('Reset password for:', this.forgotEmail)
        alert('Un lien de réinitialisation a été envoyé à votre email')
        this.closeForgotModal()
      }
    },
    
    mounted() {
  // Vérifier l'état de connexion au chargement de la page
  this.checkAuthState();
  
  // Vérifier si l'utilisateur a été redirigé depuis une route protégée
  if (this.$route.query.redirect && !this.isLoggedIn) {
    // Ouvrir automatiquement le modal de connexion
    this.openLoginModal();
  }
  
  // Animation d'entrée pour la page d'accueil
  if (this.currentPage === 'home') {
    setTimeout(() => {
      const title = document.querySelector('.hero-title');
      const subtitle = document.querySelector('.hero-subtitle');
      if (title) title.style.opacity = '1';
      if (subtitle) subtitle.style.opacity = '1';
    }, 100);
  }
},


    beforeUnmount() {
      document.body.style.overflow = 'auto'
    }
  }
</script>
  
  <style scoped>
  .landing-page {
    min-height: 100vh;
    background: #1E293B;
    color: white;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }
  
  /* Header */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 1000;
    transition: all 0.3s ease;
  }
  
  .nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
  }
  
  .logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.8rem;
    font-weight: 700;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .logo-img {
    width: 32px;
    height: 32px;
  }
  
  .nav-links {
    display: flex;
    list-style: none;
    gap: 2rem;
    align-items: center;
  }
  
  .nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
  }
  
  .nav-links a:hover {
    color: #3B82F6;
  }
  
  .nav-links a.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    border-radius: 1px;
  }
  
  .get-started-btn {
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    color: white;
    padding: 0.7rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
  }
  
  .get-started-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
  }
  
  /* Main Content */
  .main-content {
    min-height: calc(100vh - 80px);
  }
  
  /* Home Section */
  .home-section {
    padding: 4rem 0;
    text-align: center;
    position: relative;
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
  }
  
  .hero-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 0;
  }
  
  .hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #FFFFFF, #CBD5E1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: fadeInUp 1s ease-out;
  }
  
  .hero-subtitle {
    font-size: 1.2rem;
    color: #CBD5E1;
    margin-bottom: 3rem;
    animation: fadeInUp 1s ease-out 0.2s both;
  }
  
  .cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  .cta-primary, .cta-secondary {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
  }
  
  .cta-primary {
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    color: white;
  }
  
  .cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
  }
  
  .cta-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
  }
  
  .cta-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
  }
  
  /* Floating Animation */
  .floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
  }
  
  .floating-box {
    position: absolute;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    border-radius: 15px;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
  }
  
  .floating-box:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
  .floating-box:nth-child(2) { top: 60%; left: 80%; animation-delay: 1s; }
  .floating-box:nth-child(3) { top: 40%; left: 15%; animation-delay: 2s; }
  .floating-box:nth-child(4) { top: 70%; left: 70%; animation-delay: 3s; }
  
  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
  }
  
  /* About Section */
  .about-section {
    padding: 4rem 0;
    text-align: center;
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
  }
  
  .about-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    color: white;
  }
  
  .about-description {
    font-size: 1.1rem;
    color: #CBD5E1;
    max-width: 600px;
    margin: 0 auto 3rem;
    line-height: 1.8;
  }
  
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
  }
  
  .stat-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    transition: all 0.3s ease;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  }
  
  .stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
  }
  
  .stat-label {
    font-size: 1rem;
    color: #CBD5E1;
    font-weight: 500;
  }

  /* Modal Styles */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    animation: fadeIn 0.3s ease-out;
  }

  .modal-content {
    background: #1E293B;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 2rem;
    width: 90%;
    max-width: 480px;
    position: relative;
    animation: slideInUp 0.4s ease-out;
    overflow: hidden;
  }

  .modal-floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
  }

  .modal-floating-box {
    position: absolute;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    border-radius: 10px;
    opacity: 0.05;
    animation: float 8s ease-in-out infinite;
  }

  .modal-floating-box:nth-child(1) { top: 10%; right: 10%; animation-delay: 0s; }
  .modal-floating-box:nth-child(2) { bottom: 20%; left: 15%; animation-delay: 2s; }
  .modal-floating-box:nth-child(3) { top: 50%; right: 20%; animation-delay: 4s; }

  .close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 8px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
  }

  .close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
  }

  .modal-header {
    text-align: center;
    margin-bottom: 2rem;
  }

  .modal-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
  }

  .modal-logo-img {
    width: 28px;
    height: 28px;
  }

  .modal-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
  }

  .modal-subtitle {
    color: #CBD5E1;
    font-size: 0.9rem;
  }

  /* Form Styles */
  .login-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .form-label {
    font-weight: 600;
    color: white;
    font-size: 0.9rem;
  }

  .form-input {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 0.9rem 1rem;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
  }

  .form-input:focus {
    outline: none;
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: rgba(255, 255, 255, 0.08);
  }

  .form-input::placeholder {
    color: #94A3B8;
  }

  .submit-btn {
    background: linear-gradient(135deg, #3B82F6, #8B5CF6);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 1rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 0.5rem;
  }

  .submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
  }

  /* Modal Footer */
  .modal-footer {
    margin-top: 2rem;
    text-align: center;
  }

  .forgot-password {
    margin-bottom: 1.5rem;
  }

  .forgot-link {
    color: #3B82F6;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }

  .forgot-link:hover {
    color: #8B5CF6;
    text-decoration: underline;
  }

  .switch-mode {
    color: #CBD5E1;
    font-size: 0.9rem;
  }

  .switch-btn {
    background: none;
    border: none;
    color: #3B82F6;
    cursor: pointer;
    font-weight: 600;
    margin-left: 0.5rem;
    transition: all 0.3s ease;
  }

  .switch-btn:hover {
    color: #8B5CF6;
    text-decoration: underline;
  }

  /* Forgot Modal */
  .forgot-modal {
    max-width: 400px;
  }

  /* Styles pour la section utilisateur */
.user-section {
  display: flex;
  align-items: center;
  gap: 15px;
}

.user-greeting {
  color: #FFFFFF;
  font-weight: 500;
  font-size: 17px;
}

.logout-btn {
  background: #ff4757;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.logout-btn:hover {
  background: #ff3838;
  transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 768px) {
  .user-section {
    flex-direction: column;
    gap: 8px;
  }
  
  .user-greeting {
    font-size: 12px;
  }
  
  .logout-btn {
    padding: 6px 12px;
    font-size: 12px;
  }
}
  
  /* Animations */
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideInUp {
    from {
      opacity: 0;
      transform: translateY(30px) scale(0.9);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .nav-links {
      display: none;
    }
    
    .hero-title {
      font-size: 2.5rem;
    }
    
    .stats-grid {
      grid-template-columns: 1fr;
    }
  
    .cta-buttons {
      flex-direction: column;
      align-items: center;
    }

    .modal-content {
      margin: 1rem;
      padding: 1.5rem;
    }

    .modal-title {
      font-size: 1.5rem;
    }
  }
  </style>