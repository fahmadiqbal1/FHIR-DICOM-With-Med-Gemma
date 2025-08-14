<style>
/* Global CSS Variables for Consistent Color Scheme */
:root {
    /* Primary gradient colors */
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --primary-gradient-hover: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    
    /* Individual colors from the gradient */
    --primary-blue: #667eea;
    --primary-purple: #764ba2;
    --primary-blue-hover: #5a6fd8;
    --primary-purple-hover: #6a4190;
    
    /* Glass morphism effects */
    --glass-background: rgba(255, 255, 255, 0.1);
    --glass-background-hover: rgba(255, 255, 255, 0.15);
    --glass-background-active: rgba(255, 255, 255, 0.2);
    --glass-border: rgba(255, 255, 255, 0.2);
    --glass-border-hover: rgba(255, 255, 255, 0.3);
    
    /* Text colors */
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.8);
    --text-muted: rgba(255, 255, 255, 0.6);
    --text-light: rgba(255, 255, 255, 0.4);
    
    /* Background colors */
    --bg-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --bg-card: rgba(255, 255, 255, 0.1);
    --bg-card-hover: rgba(255, 255, 255, 0.15);
    --bg-input: rgba(255, 255, 255, 0.1);
    --bg-input-focus: rgba(255, 255, 255, 0.15);
    
    /* Button styles */
    --btn-primary-bg: rgba(255, 255, 255, 0.2);
    --btn-primary-border: rgba(255, 255, 255, 0.3);
    --btn-primary-hover: rgba(255, 255, 255, 0.3);
    --btn-ghost-bg: transparent;
    --btn-ghost-hover: rgba(255, 255, 255, 0.1);
    
    /* Shadow and effects */
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.15);
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.2);
    --backdrop-blur: blur(15px);
    --backdrop-blur-strong: blur(20px);
    
    /* Border radius */
    --radius-sm: 6px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 15px;
    --radius-full: 50%;
    
    /* Spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    
    /* Status colors with transparency */
    --success-bg: rgba(34, 197, 94, 0.2);
    --success-color: #22c55e;
    --success-border: rgba(34, 197, 94, 0.3);
    
    --warning-bg: rgba(251, 191, 36, 0.2);
    --warning-color: #fbbf24;
    --warning-border: rgba(251, 191, 36, 0.3);
    
    --error-bg: rgba(239, 68, 68, 0.2);
    --error-color: #ef4444;
    --error-border: rgba(239, 68, 68, 0.3);
    
    --info-bg: rgba(59, 130, 246, 0.2);
    --info-color: #3b82f6;
    --info-border: rgba(59, 130, 246, 0.3);
}

/* Base body styling */
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    min-height: 100vh;
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

/* Common utility classes */
.gradient-bg {
    background: var(--primary-gradient);
}

.glass-effect {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
}

.glass-effect:hover {
    background: var(--glass-background-hover);
    border-color: var(--glass-border-hover);
}

.shadow-sm { box-shadow: var(--shadow-sm); }
.shadow-md { box-shadow: var(--shadow-md); }
.shadow-lg { box-shadow: var(--shadow-lg); }

.text-primary { color: var(--text-primary); }
.text-secondary { color: var(--text-secondary); }
.text-muted { color: var(--text-muted); }
.text-light { color: var(--text-light); }
</style>
