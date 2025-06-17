<template>
    <div class="ai-copilot-app">
      <!-- Hero Section -->
      <section class="hero-section">
        <div class="container">
          <div class="hero-content">
            <div class="hero-text">
              <h1 class="hero-title">Your AI Copilot for Stockeo</h1>
              <p class="hero-subtitle">
                Supercharge your productivity with our intelligent AI assistant. From coding to writing, 
                creative tasks to data analysis - your AI copilot is ready to help you achieve more.
              </p>
              <div class="cta-buttons">
                <button class="cta-primary" @click="startFreeTrial">Start Free Trial</button>
                <button class="cta-secondary" @click="watchDemo">Watch Demo</button>
              </div>
            </div>
            
            <div class="ai-bot-container">
              <div class="ai-bot">
                <div class="bot-body">
                  <div class="bot-head">
                    <div class="bot-eyes">
                      <div class="bot-eye"></div>
                      <div class="bot-eye"></div>
                    </div>
                  </div>
                  <div class="bot-antenna"></div>
                  <div class="bot-arms">
                    <div class="bot-arm left"></div>
                    <div class="bot-arm right"></div>
                  </div>
                </div>
              </div>
              <div class="floating-particles">
                <div class="particle" v-for="n in 4" :key="n"></div>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Pricing Section -->
      <section class="pricing-section">
        <div class="container">
          <h2 class="pricing-title">Choose Your AI Plan</h2>
          <p class="pricing-subtitle">
            Select the perfect plan that fits your needs. All plans include our core AI features with varying levels of usage and support.
          </p>
          
          <div class="pricing-grid">
            <div 
              v-for="plan in pricingPlans" 
              :key="plan.id"
              class="pricing-card"
              :class="{ popular: plan.popular }"
              @click="selectPlan(plan)"
            >
              <h3 class="plan-name">{{ plan.name }}</h3>
              <div class="plan-price">{{ plan.price }}</div>
              <div class="plan-period">{{ plan.period }}</div>
              <p class="plan-description">{{ plan.description }}</p>
              
              <ul class="plan-features">
                <li v-for="feature in plan.features" :key="feature">{{ feature }}</li>
              </ul>
              
              <button 
                class="plan-button"
                :class="plan.buttonClass"
                @click.stop="selectPlan(plan)"
              >
                {{ plan.buttonText }}
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue'
  
  export default {
    name: 'AICopilot',
    setup() {
      const pricingPlans = ref([
        {
          id: 'free',
          name: 'Free',
          price: '€0',
          period: 'Forever free',
          description: 'Perfect for individuals getting started with AI assistance. Basic features with limited usage.',
          features: [
            '50 AI queries per month',
            'Basic code assistance',
            'Text generation',
            'Community support',
            'Web interface access'
          ],
          buttonText: 'Get Started Free',
          buttonClass: 'secondary',
          popular: false
        },
        {
          id: 'standard',
          name: 'Standard',
          price: '€30',
          period: 'per month',
          description: 'Ideal for professionals and small teams. Enhanced features with generous usage limits and priority support.',
          features: [
            '2,000 AI queries per month',
            'Advanced code generation',
            'Document analysis',
            'Priority email support',
            'API access',
            'Custom templates',
            'Export capabilities'
          ],
          buttonText: 'Start Standard Plan',
          buttonClass: 'primary',
          popular: true
        },
        {
          id: 'golden',
          name: 'Golden',
          price: '€50',
          period: 'per month',
          description: 'Maximum power for enterprises and power users. Unlimited usage with premium features and dedicated support.',
          features: [
            'Unlimited AI queries',
            'Advanced AI models',
            'Team collaboration',
            '24/7 priority support',
            'Full API access',
            'Custom integrations',
            'Advanced analytics',
            'White-label options'
          ],
          buttonText: 'Go Golden',
          buttonClass: 'primary',
          popular: false
        }
      ])
  
      const startFreeTrial = () => {
        console.log('Starting free trial...')
        // Add your logic here
      }
  
      const watchDemo = () => {
        console.log('Opening demo...')
        // Add your logic here
      }
  
      const selectPlan = (plan) => {
        console.log('Selected plan:', plan.name)
        // Add your plan selection logic here
      }
  
      onMounted(() => {
        // Add any initialization logic here
      })
  
      return {
        pricingPlans,
        startFreeTrial,
        watchDemo,
        selectPlan
      }
    }
  }
  </script>
  
  <style scoped>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  .ai-copilot-app {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
    color: white;
    overflow-x: hidden;
    min-height: 100vh;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }
  
  /* Hero Section */
  .hero-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
  }
  
  .hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    width: 100%;
  }
  
  .hero-text {
    z-index: 2;
  }
  
  .hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #FFFFFF, #06B6D4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: fadeInUp 1s ease-out;
  }
  
  .hero-subtitle {
    font-size: 1.2rem;
    color: #CBD5E1;
    margin-bottom: 3rem;
    line-height: 1.6;
    animation: fadeInUp 1s ease-out 0.2s both;
  }
  
  .cta-buttons {
    display: flex;
    gap: 1rem;
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
    background: linear-gradient(135deg, #06B6D4, #3B82F6);
    color: white;
  }
  
  .cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
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
  
  /* AI Bot Animation */
  .ai-bot-container {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .ai-bot {
    width: 300px;
    height: 300px;
    position: relative;
    animation: float 3s ease-in-out infinite;
  }
  
  .bot-body {
    width: 200px;
    height: 240px;
    background: linear-gradient(135deg, #06B6D4, #3B82F6, #8B5CF6);
    border-radius: 40px;
    position: relative;
    margin: 0 auto;
    box-shadow: 0 20px 60px rgba(6, 182, 212, 0.3);
  }
  
  .bot-head {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #1E293B, #334155);
    border-radius: 50%;
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid #06B6D4;
  }
  
  .bot-eyes {
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: center;
    height: 100%;
  }
  
  .bot-eye {
    width: 20px;
    height: 20px;
    background: #06B6D4;
    border-radius: 50%;
    animation: blink 3s infinite;
  }
  
  .bot-antenna {
    width: 3px;
    height: 30px;
    background: #06B6D4;
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
  }
  
  .bot-antenna::after {
    content: '';
    width: 12px;
    height: 12px;
    background: #3B82F6;
    border-radius: 50%;
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    animation: pulse 2s infinite;
  }
  
  .bot-arms {
    position: absolute;
    top: 100px;
    width: 100%;
  }
  
  .bot-arm {
    width: 60px;
    height: 12px;
    background: linear-gradient(135deg, #06B6D4, #3B82F6);
    border-radius: 6px;
    position: absolute;
  }
  
  .bot-arm.left {
    left: -30px;
    animation: wave-left 2s ease-in-out infinite alternate;
  }
  
  .bot-arm.right {
    right: -30px;
    animation: wave-right 2s ease-in-out infinite alternate;
  }
  
  .floating-particles {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
  }
  
  .particle {
    position: absolute;
    width: 6px;
    height: 6px;
    background: #06B6D4;
    border-radius: 50%;
    opacity: 0.7;
    animation: float-particle 4s ease-in-out infinite;
  }
  
  .particle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
  .particle:nth-child(2) { top: 60%; left: 80%; animation-delay: 1s; }
  .particle:nth-child(3) { top: 40%; left: 20%; animation-delay: 2s; }
  .particle:nth-child(4) { top: 80%; left: 70%; animation-delay: 3s; }
  
  /* Pricing Section */
  .pricing-section {
    padding: 6rem 0;
    background: rgba(0, 0, 0, 0.2);
  }
  
  .pricing-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #FFFFFF, #06B6D4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .pricing-subtitle {
    text-align: center;
    font-size: 1.1rem;
    color: #CBD5E1;
    margin-bottom: 4rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
  }
  
  .pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
  }
  
  .pricing-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 2.5rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
  }
  
  .pricing-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(6, 182, 212, 0.2);
    border-color: #06B6D4;
  }
  
  .pricing-card.popular {
    border-color: #06B6D4;
    box-shadow: 0 20px 40px rgba(6, 182, 212, 0.3);
  }
  
  .pricing-card.popular::before {
    content: 'Most Popular';
    position: absolute;
    top: -1px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #06B6D4, #3B82F6);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 0 0 12px 12px;
    font-size: 0.8rem;
    font-weight: 600;
  }
  
  .plan-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
  }
  
  .plan-price {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #06B6D4, #3B82F6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .plan-period {
    color: #94A3B8;
    margin-bottom: 2rem;
  }
  
  .plan-description {
    color: #CBD5E1;
    margin-bottom: 2rem;
    line-height: 1.6;
  }
  
  .plan-features {
    list-style: none;
    margin-bottom: 2rem;
  }
  
  .plan-features li {
    padding: 0.5rem 0;
    color: #CBD5E1;
    position: relative;
    padding-left: 1.5rem;
  }
  
  .plan-features li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #06B6D4;
    font-weight: bold;
  }
  
  .plan-button {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
  }
  
  .plan-button.primary {
    background: linear-gradient(135deg, #06B6D4, #3B82F6);
    color: white;
  }
  
  .plan-button.secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
  }
  
  .plan-button:hover {
    transform: translateY(-2px);
  }
  
  .plan-button.primary:hover {
    box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
  }
  
  .plan-button.secondary:hover {
    background: rgba(255, 255, 255, 0.2);
  }
  
  /* Animations */
  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
  }
  
  @keyframes blink {
    0%, 90%, 100% { opacity: 1; }
    95% { opacity: 0.3; }
  }
  
  @keyframes pulse {
    0%, 100% { transform: translateX(-50%) scale(1); opacity: 1; }
    50% { transform: translateX(-50%) scale(1.2); opacity: 0.7; }
  }
  
  @keyframes wave-left {
    0% { transform: rotate(-10deg); }
    100% { transform: rotate(-30deg); }
  }
  
  @keyframes wave-right {
    0% { transform: rotate(10deg); }
    100% { transform: rotate(30deg); }
  }
  
  @keyframes float-particle {
    0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
    50% { transform: translateY(-30px) rotate(180deg); opacity: 1; }
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
    .hero-content {
      grid-template-columns: 1fr;
      gap: 2rem;
      text-align: center;
    }
  
    .hero-title {
      font-size: 2.5rem;
    }
  
    .ai-bot {
      width: 250px;
      height: 250px;
    }
  
    .bot-body {
      width: 160px;
      height: 200px;
    }
  
    .bot-head {
      width: 100px;
      height: 100px;
    }
  
    .pricing-grid {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
  
    .cta-buttons {
      justify-content: center;
    }
  }
  </style>