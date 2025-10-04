# Medical AI Chatbot - Comprehensive Healthcare Assistant

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/AI-OpenAI%20GPT--4o-green?style=for-the-badge&logo=openai" alt="OpenAI GPT-4o">
  <img src="https://img.shields.io/badge/UI-TailwindCSS-cyan?style=for-the-badge&logo=tailwindcss" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Database-SQLite-blue?style=for-the-badge&logo=sqlite" alt="SQLite">
</p>

## About Medical AI Chatbot

A comprehensive AI-powered healthcare assistant built with Laravel 12 and OpenAI's GPT-4o. This advanced medical chatbot provides users with intelligent health guidance, symptom analysis, medical report interpretation, and personalized health planning. The system features multilingual support (15+ languages), camera integration for document capture, and a complete doctor discovery platform with FREE mapping capabilities.

## 🚀 Key Features

### 🤖 Advanced AI Medical Assistant
- **OpenAI GPT-4o Integration** - Professional-grade medical AI responses
- **Three Specialized Modes**:
  - **General Health**: Wellness guidance and health education
  - **Symptom Discussion**: Structured symptom analysis with clarifying questions
  - **Health Advice**: Evidence-based recommendations and self-care guidance
- **Voice Input Support** - Web Speech API with cross-browser compatibility
- **Chat Persistence** - Auto-save conversations with history management
- **Emergency Detection** - Automatic identification of critical symptoms

### 🔍 Intelligent Symptom Checker
- **AI-Powered Analysis** - Comprehensive symptom evaluation using GPT-4o
- **Urgency Assessment** - Risk stratification (Low, Medium, High, Critical)
- **Structured Questioning** - Systematic symptom exploration
- **Professional Recommendations** - When to seek medical care
- **Symptom Tracking** - Historical symptom patterns and trends

### 📋 Advanced Report Analyzer
- **Multi-Format Support** - PDF, DOC, DOCX, TXT, JPG, PNG, GIF, WEBP (up to 20MB)
- **Camera Integration** - Real-time document capture with front/back camera support
- **GPT-4 Vision Analysis** - Professional medical document interpretation
- **Comprehensive Analysis Sections**:
  - Document Overview & Clinical Context
  - Detailed Findings & Lab Values
  - Medical Interpretation & Pathophysiology
  - Patient Education (jargon-free explanations)
  - Risk Assessment & Health Implications
  - Actionable Recommendations & Follow-up Care
  - Emotional Support & Reassurance
- **No Report Type Selection** - Automatic document type detection

### 🏥 Doctor Discovery Platform
- **FREE OpenStreetMap Integration** - No API keys required
- **Location-Based Search** - "Near Me" functionality with geolocation
- **Disease-to-Specialty Mapping** - Intelligent specialist recommendations
- **Interactive Map View** - Custom markers and rich popups
- **Comprehensive Database** - 15+ specialties with realistic doctor profiles
- **Advanced Filtering** - By specialty, location, rating, and availability

### 📊 Health Metrics Tracking
- **Multiple Metrics** - Blood pressure, glucose, weight, heart rate, temperature
- **Interactive Charts** - Chart.js visualizations with trend analysis
- **Historical Data** - Long-term health monitoring
- **Export Capabilities** - Data export for healthcare providers

### 🌍 Multilingual Support
- **15+ Languages** - Including RTL support for Arabic, Urdu, Persian
- **Auto-Detection** - Smart language identification from user input
- **Cultural Context** - Medically appropriate responses for each culture
- **Emergency Localization** - Critical health warnings in native languages

### 🎯 Personalized Health Plans
- **AI-Generated Plans** - Customized health and wellness strategies
- **Goal Tracking** - Progress monitoring and milestone achievements
- **Template Library** - Pre-built plans for common health goals
- **Lifestyle Integration** - Diet, exercise, and wellness recommendations

### 🔒 Authentication & Security
- **Laravel Breeze** - Secure authentication system
- **OAuth Integration** - Google & GitHub social login
- **Data Encryption** - All health data encrypted and secured
- **Privacy Compliance** - GDPR-ready data handling

## 🛠 Technology Stack

### Backend
- **Framework**: Laravel 12.0
- **Language**: PHP 8.2+
- **Database**: SQLite (default), MySQL/PostgreSQL support
- **AI Services**: 
  - OpenAI GPT-4o (primary)
  - Google Gemini (legacy support)
- **Authentication**: Laravel Breeze + Socialite (Google, GitHub)
- **Real-time**: Pusher WebSockets
- **File Processing**: Multi-format document handling

### Frontend
- **CSS Framework**: TailwindCSS 4.0 with @tailwindcss/forms
- **JavaScript**: Alpine.js 3.x
- **Charts**: Chart.js 4.4
- **Maps**: Leaflet.js + OpenStreetMap (FREE)
- **Icons**: Heroicons
- **Build Tool**: Vite 7.0

### Key Dependencies
```json
{
  "laravel/framework": "^12.0",
  "laravel/breeze": "^2.3",
  "laravel/socialite": "^5.23",
  "guzzlehttp/guzzle": "^7.10",
  "spatie/laravel-permission": "^6.21",
  "pusher/pusher-php-server": "^7.2"
}
```

## 📋 Prerequisites

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18+ with NPM
- **Database**: SQLite (included) or MySQL/PostgreSQL
- **OpenAI API Key**: For AI functionality
- **Optional**: Google/GitHub OAuth credentials, Pusher account

## 🚀 Quick Start Installation

### 1. Clone & Setup
```bash
git clone <repository-url>
cd medical-chatbot
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Environment Variables
Edit `.env` file:

```env
APP_NAME="Medical AI"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite - No setup required)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# AI Configuration (Required)
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=gpt-4o
OPENAI_MAX_TOKENS=2048

# OAuth (Optional)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret

# Real-time Chat (Optional)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=mt1
```

### 4. Database & Assets
```bash
# Setup database
php artisan migrate
php artisan db:seed  # Optional: Sample data

# Build frontend
npm run build  # Production
# OR
npm run dev    # Development
```

### 5. Launch Application
```bash
php artisan serve
# Visit: http://localhost:8000
```

## ⚙️ Configuration Guide

### OpenAI API Setup (Required)
1. Visit [OpenAI Platform](https://platform.openai.com/api-keys)
2. Create API key
3. Add to `.env`: `OPENAI_API_KEY=your_key_here`

### OAuth Setup (Optional)
#### Google OAuth
1. [Google Cloud Console](https://console.cloud.google.com/) → Create project
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Authorized redirect: `http://localhost:8000/auth/google/callback`

#### GitHub OAuth
1. GitHub Settings → Developer settings → OAuth Apps
2. New OAuth App
3. Authorization callback: `http://localhost:8000/auth/github/callback`

### Real-time Chat (Optional)
1. Create [Pusher](https://pusher.com/) account
2. Create new app
3. Add credentials to `.env`

## 📱 Usage Guide

### 🏠 Dashboard
- **Health Overview**: Metrics summary and trends
- **Recent Activity**: Chat history and health records
- **Quick Actions**: Direct access to all features
- **AI Insights**: Personalized health recommendations

### 💬 AI Chat Assistant
- **Three Modes**: General Health, Symptom Discussion, Health Advice
- **Voice Input**: Click microphone for speech-to-text
- **Conversation History**: Auto-saved with manual save/load options
- **Multilingual**: Automatic language detection and response

### 🔍 Symptom Checker
- **Smart Analysis**: Describe symptoms in natural language
- **Urgency Levels**: Color-coded risk assessment
- **Follow-up Questions**: AI asks clarifying questions
- **Professional Guidance**: When to seek medical care

### 📋 Report Analyzer
- **Upload Methods**: Drag-drop files or camera capture
- **Comprehensive Analysis**: 8+ detailed sections
- **Professional Quality**: Hospital-grade medical interpretation
- **Patient-Friendly**: Clear explanations without medical jargon

### 🏥 Doctor Discovery
- **Map View**: Interactive OpenStreetMap with doctor locations
- **Smart Search**: Disease-based specialist recommendations
- **Location Services**: "Near Me" with GPS integration
- **Detailed Profiles**: Specialties, languages, insurance, ratings

### 📊 Health Metrics
- **Multiple Tracking**: BP, glucose, weight, heart rate, temperature
- **Visual Trends**: Interactive Chart.js graphs
- **Goal Setting**: Target ranges and progress monitoring
- **Data Export**: Share with healthcare providers

## 🔒 Security & Privacy

### Data Protection
- **Encryption**: All health data encrypted at rest and in transit
- **Authentication**: Secure Laravel Breeze implementation
- **Session Management**: Configurable session timeouts
- **File Security**: Secure upload handling with validation

### Medical Safety
- **Professional Disclaimers**: Clear limitations and recommendations
- **Emergency Detection**: Automatic identification of critical symptoms
- **No Diagnosis Claims**: Educational information only
- **Healthcare Provider Referrals**: Encourages professional consultation

### Privacy Compliance
- **GDPR Ready**: Data protection and user rights
- **Minimal Data Collection**: Only necessary health information
- **User Control**: Data export and deletion capabilities
- **Secure Storage**: Local SQLite database by default

## 🌟 Advanced Features

### Multilingual AI Responses
- **15+ Languages**: English, Arabic, Spanish, French, German, Italian, Portuguese, Russian, Chinese, Japanese, Korean, Hindi, Turkish, Urdu, Persian
- **RTL Support**: Right-to-left layout for Arabic, Urdu, Persian
- **Cultural Context**: Medically appropriate responses for each culture
- **Auto-Detection**: Smart language identification from user input

### Camera Integration
- **Real-time Capture**: Live camera preview with capture controls
- **Multi-Camera Support**: Front/back camera selection
- **High Quality**: Optimized image capture for document analysis
- **Seamless Integration**: Direct integration with report analyzer

### FREE Mapping Solution
- **No API Keys**: OpenStreetMap + Nominatim (completely free)
- **No Limits**: Unlimited map views and geocoding requests
- **Rich Features**: Custom markers, popups, distance calculation
- **Mobile Optimized**: Responsive design for all devices

## 🚨 Medical Disclaimer

**IMPORTANT NOTICE**: This application provides general health information and educational content only. It is NOT intended to replace professional medical advice, diagnosis, or treatment.

- **Always consult qualified healthcare providers** for medical concerns
- **Emergency situations**: Contact emergency services immediately (911, 999, etc.)
- **No diagnostic claims**: This system provides information, not medical diagnoses
- **Professional consultation required**: For all health decisions and treatments

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork** the repository
2. **Create** feature branch: `git checkout -b feature/amazing-feature`
3. **Commit** changes: `git commit -m 'Add amazing feature'`
4. **Push** to branch: `git push origin feature/amazing-feature`
5. **Open** Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Add tests for new features
- Update documentation
- Maintain medical safety protocols

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Framework** - Robust PHP framework
- **OpenAI** - Advanced AI capabilities
- **TailwindCSS** - Modern UI framework
- **OpenStreetMap** - Free mapping solution
- **Chart.js** - Interactive data visualization
- **All Contributors** - Community support and contributions

## 📞 Support & Documentation

### Getting Help
- **Issues**: Create GitHub issue for bugs/features
- **Documentation**: Check project wiki and inline comments
- **Medical Questions**: Consult healthcare professionals
- **Technical Support**: Review setup guides and error logs

### Additional Resources
- `OPENAI_MIGRATION.md` - AI service migration guide
- `GOOGLE_MAPS_SETUP.md` - Mapping configuration
- `MYSQL_SETUP.md` - Database alternatives
- Inline code documentation and comments

---

**Built with ❤️ for better healthcare accessibility**

