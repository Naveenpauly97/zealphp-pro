# ZealPHP Landing Page Implementation

A complete Yukthi landing page implementation for Selfmade Ninja Academy built with ZealPHP framework, featuring a responsive design, contact form with backend integration, and admin dashboard.

## 🚀 Features

- **Responsive Design**: Mobile-first approach with perfect desktop, tablet, and mobile layouts
- **Contact Form**: Lead capture with server-side validation and CSRF protection
- **Database Integration**: MySQL storage for leads with proper indexing
- **SEO Optimized**: Meta tags, structured data, and semantic HTML
- **Accessibility**: WCAG compliant with proper ARIA labels and keyboard navigation
- **Performance**: Optimized images, CSS, and JavaScript loading

## 📁 Project Structure

```
├── app/
│   ├── Controllers/
│   │   └── LandingController.php    # Main landing page logic
│   └── Models/
│       └── Lead.php                 # Lead model for database operations
├── template/
│   └── landing/
│       ├── common
│       │     ├── __head.php  
│       │     └── __header.php   
│       ├── landing_section
│       │     ├── cyber.php  
│       │     ├── expert-section.php  
│       │     ├── hero.php  
│       │     ├── video-section.php 
│       │     └── testimonials.php         
│       ├── index.php                  # Main landing page template
│       ├── main.php 
│       └── contact.php 
├── route/
│   └── landing.php                    # Landing page routes
├── public/
│   ├── css/
│   │    ├── contact.css
│   │    └── styles.css                # Responsive landing page styles
│   └── js/
│       └── landing.js                 # Responsive landing page styles
├── DDL/
│   └── taskddl.sql                    # Database schema and sample data
├── config/
│   └── database.php                   # Enhanced database configuration
├── .env                               # Environment configuration template
└── README_LANDING.md                  # This file
```

## 🛠️ Setup Instructions

### Prerequisites

- PHP 8.2+ with OpenSwoole ≥ v22.1
- uopz extension
- MySQL 8.0+
- Composer

### Installation

1. **Clone and navigate to project**
   ```bash
   cd zealphp-pro
   ```

4. **Start the server[Docker setup]**
   ```bash
   docker compose up
   ``` 

# OR

1. **Clone and navigate to project**
   ```bash
   cd zealphp-pro
   ```

2. **Install dependencies** (if not already done)
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   # Edit .env with your database credentials
   ```

4. **Start the server**
   ```bash
   php app.php
   ```

5. **Access the application**
   - Landing page: `http://localhost:8080/`
   - Contact dashboard: `http://localhost:8080/contact/lead-capture` [or click register now button in landing page]

## 🎯 Routes

### Web Routes
- `GET /` - Landing page
- `GET /contact/lead-capture` - Contact page
- `POST /contact` - Contact form submission (redirects back with flash messages)

### API Routes
- `POST /api/contact` - Contact form API endpoint (returns JSON)


## 📊 Database Schema

### Leads Table
```sql
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NULL,
    message TEXT NULL,
    image TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);
```

## 🎨 Design Features

### Responsive Breakpoints
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px  
- **Desktop**: > 1024px

### Color System
- **Primary**: Orange (#f47923) - Brand color
- **Neutral**: Gray scale for text and backgrounds
- **Success**: Green (#22c55e) for success states
- **Error**: Red (#ef4444) for error states

### Typography
- **Headings**: Inter font family with proper hierarchy
- **Body**: System font stack for optimal performance
- **Line Heights**: 1.25 for headings, 1.5 for body text

## 🔒 Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation for all form fields
- **SQL Injection Prevention**: Prepared statements for all database queries
- **XSS Prevention**: All output is properly escaped

## 📱 Accessibility Features

- **Semantic HTML**: Proper use of header, main, section, article tags
- **ARIA Labels**: Screen reader friendly navigation
- **Keyboard Navigation**: Full keyboard accessibility
- **Focus States**: Clear focus indicators for all interactive elements
- **Alt Text**: Descriptive alt text for all images

## 🚀 Performance Optimizations

- **Image Optimization**: WebP format with fallbacks, proper sizing
- **CSS**: Minified and optimized for critical path
- **JavaScript**: Deferred loading and minimal bundle size
- **Database**: Proper indexing on frequently queried columns

## 🧪 Testing the Implementation

### Manual Testing Checklist

1. **Landing Page**
   - [ ] Page loads correctly at `http://localhost:8081/`
   - [ ] All sections render properly
   - [ ] Images load correctly
   - [ ] Responsive design works on different screen sizes

2. **Contact Form**
   - [ ] Form validation works (try submitting empty form)
   - [ ] Success message appears after valid submission
   - [ ] Error messages display for invalid data
   - [ ] CSRF protection prevents unauthorized submissions

3. **Database Integration**
   - [ ] Leads are saved to database
   - [ ] Duplicate email handling works
   - [ ] Admin dashboard shows leads correctly

4. **API Endpoint**
   ```bash Note [csrf_token is generated and in page itself need to replace the actual csrf_token]
   # Test API endpoint with curl
      curl --location 'http://localhost:8081/api/contact' \
      --header 'Content-Type: application/json' \
      --form 'firstName="kannudd"' \
      --form 'lastName="Kumardd"' \
      --form 'email="Naveen1234d@gmail.com"' \
      --form 'phone="0845715249"' \
      --form 'message="Hi this is first app"' \
      --form 'csrf_token="7653db6303f77a964abf1c2d2e8484787a092bc29b6488920fd649a2b14eac32"'
   ```

## 🔧 Configuration

### Environment Variables

Key variables in `.env`:

```env
# Database
DB_HOST=127.0.0.1
DB_DATABASE=zealphp
DB_USERNAME=root
DB_PASSWORD=your_password

# Application
APP_URL=http://localhost:8081
APP_ENV=development
APP_DEBUG=true
```

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check `.env` file configuration
   - Ensure MySQL service is running
   - Verify database exists and user has permissions

2. **CSRF Token Mismatch**
   - Clear browser cache and cookies
   - Ensure session is working properly

3. **Form Not Submitting**
   - Check browser console for JavaScript errors
   - Verify form action URL is correct
   - Ensure CSRF token is present

### Debug Mode

Enable debug mode in `.env`:
```env
APP_DEBUG=true
```

This will show detailed error messages and stack traces.

## 📞 Support

For issues specific to this landing page implementation:
1. Check the ZealPHP framework documentation
2. Review the error logs in your application
3. Ensure all dependencies are properly installed

## 🏆 Best Practices Implemented

- **MVC Architecture**: Clean separation of concerns
- **Security First**: CSRF protection, input validation, output escaping
- **Performance**: Optimized assets and database queries
- **Accessibility**: WCAG 2.1 AA compliance
- **SEO**: Proper meta tags and structured data
- **Responsive**: Mobile-first design approach