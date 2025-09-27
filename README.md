# Medical Chatbot - AI-Powered Healthcare Assistant

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/AI-Gemini-green?style=for-the-badge&logo=google" alt="Gemini AI">
  <img src="https://img.shields.io/badge/UI-TailwindCSS-cyan?style=for-the-badge&logo=tailwindcss" alt="TailwindCSS">
</p>

## About Medical Chatbot

The Medical Chatbot is a comprehensive AI-powered healthcare assistant built with Laravel 12 and PHP. It provides users with a secure platform for health-related inquiries, symptom checks, and personalized health tracking. The system integrates Google's Gemini AI for intelligent responses and supports real-time chat interactions.

## Features

### Authentication & Security
- Laravel Breeze authentication system
- OAuth integration (Google & GitHub)
- Secure session management
- Data privacy protection

### AI-Powered Medical Assistant
- Integration with Google Gemini AI
- Context-aware conversations
- Medical safety prompts and disclaimers
- Intelligent health guidance

### Symptom Checker
- AI-powered symptom analysis
- Urgency level assessment
- Personalized recommendations
- Doctor consultation suggestions

### Health Metrics Tracking
- Blood pressure monitoring
- Blood sugar tracking
- Weight management
- Heart rate monitoring
- Temperature logging
- Interactive Chart.js visualizations

### Real-Time Chat
- WebSocket-powered real-time messaging
- Voice input support (Speech-to-Text)
- Conversation history
- Multiple chat types (General, Symptom Check, Health Advice)

### Modern UI/UX
- Responsive design with TailwindCSS
- Intuitive dashboard
- Mobile-friendly interface
- Accessibility features

## Technology Stack

### Backend
- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Database**: SQLite/MySQL/PostgreSQL
- **AI Integration**: Google Gemini AI
- **Authentication**: Laravel Breeze + Socialite
- **Real-time**: Pusher/WebSockets

### Frontend
- **CSS Framework**: TailwindCSS 4.0
- **JavaScript**: Alpine.js
- **Charts**: Chart.js
- **Icons**: Heroicons
- **Build Tool**: Vite

### Key Packages
- `laravel/socialite` - OAuth authentication
- `guzzlehttp/guzzle` - HTTP client for API requests
- `spatie/laravel-permission` - Role and permission management
- `pusher/pusher-php-server` - Real-time functionality

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL
- Google Gemini AI API key

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd medical-chatbot
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Environment Variables
Edit your `.env` file with the following configurations:

```env
# Database Configuration
DB_CONNECTION=sqlite
# DB_DATABASE=/path/to/database.sqlite

# AI Configuration
GEMINI_API_KEY=your_gemini_api_key_here

# OAuth Configuration (Optional)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"

# Broadcasting Configuration (Optional - for real-time chat)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_cluster
```

### 5. Database Setup
```bash
# Run migrations
php artisan migrate

# (Optional) Seed database
php artisan db:seed
```

### 6. Build Assets
```bash
# Build frontend assets
npm run build

# Or for development
npm run dev
```

### 7. Start the Application
```bash
# Start Laravel development server
php artisan serve

# The application will be available at http://localhost:8000
```

## Configuration

### Gemini AI Setup
1. Visit [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Create a new API key
3. Add the key to your `.env` file as `GEMINI_API_KEY`

### OAuth Setup (Optional)
#### Google OAuth
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add authorized redirect URI: `http://localhost:8000/auth/google/callback`

#### GitHub OAuth
1. Go to GitHub Settings > Developer settings > OAuth Apps
2. Create a new OAuth App
3. Set Authorization callback URL: `http://localhost:8000/auth/github/callback`

### Real-time Chat Setup (Optional)
1. Create account at [Pusher](https://pusher.com/)
2. Create a new app
3. Add credentials to `.env` file

## Usage

### Dashboard
- View health metrics overview
- Access recent conversations
- Quick access to symptom checker
- Health trends visualization

### AI Chat
- Start conversations with medical AI
- Get health advice and information
- Voice input support
- Real-time messaging

### Symptom Checker
- Input symptoms for AI analysis
- Receive urgency level assessment
- Get personalized recommendations
- Track symptom history

### Health Metrics
- Record various health measurements
- View trends with interactive charts
- Set medication reminders
- Export health data

## Security & Privacy

- All health data is encrypted and secured
- GDPR compliant data handling
- Secure authentication mechanisms
- Medical disclaimers and safety warnings
- No diagnostic claims - informational only

## Medical Disclaimer

**IMPORTANT**: This application provides general health information and should not replace professional medical advice, diagnosis, or treatment. Always consult with qualified healthcare providers for medical concerns. In case of emergency, contact emergency services immediately.

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Laravel Framework team
- Google Gemini AI team
- TailwindCSS team
- Chart.js contributors
- All open-source contributors

## Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the medical disclaimer
