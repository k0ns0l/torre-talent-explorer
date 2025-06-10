# Torre Explorer - AI/LLM Development Prompts

This document contains all the prompts used to build the Torre Explorer application, organized chronologically.

## Initial Project Setup

### Prompt 1: Create Laravel Project with Docker
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Create a new Laravel 12 project with Docker setup using PHP 8.2, MariaDB, and Nginx. Include proper Docker configuration with supervisord for process management."

### Prompt 2: Configure Database and Environment
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Set up the database configuration for both SQLite (development) and MariaDB (production). Configure environment variables and session management."

### Prompt 3: Install Frontend Dependencies
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Add Tailwind CSS, Vite, and Axios to the project. Configure the build process for modern frontend development with Laravel."

### Prompt 4: Create Authentication System
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Implement user authentication with registration and login functionality. Create the necessary views and controllers."

## Database & Models

### Prompt 5: Create Database Models
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Create models and migrations for User, Favorite, and Search-history. Set up proper relationships and database schema."

### Prompt 6: Set up Torre.ai API Integration
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Create controllers & routes (auth:web/api) to integrate with Torre.ai API for searching people, opportunities, favourites. Handle API responses and error cases."

## Core Features Development

### Prompt 7: Build Talent Search Interface
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Create a talent search page with advanced filtering, profile cards, and interactive features like selection checkboxes for bulk operations."

### Prompt 8: Implement Opportunity Search
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Build job opportunity search functionality with location, skills, and remote work filters. Display results in an attractive card layout."

### Prompt 9: Create Profile Detail Pages
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Design comprehensive profile detail pages showing skills, experience, languages, and interests with visual progress bars and statistics."

### Prompt 10: Add Analytics Features
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Implement skills analysis, profile comparison, and CSV export functionality for selected profiles."

### Prompt 11: Build Connectivity Analysis
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Create network connectivity analysis to find shared skills, companies, and locations between profiles."

### Prompt 12: Implement Favorites System
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Add ability for authenticated users to save favorite profiles/opportunities with a dedicated favorites page."

## Bug Fixes & Improvements

### Prompt 13: Fix Broken Links
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "The <opportunities> and <people> correctly show their data, except that the hyperlink for each (i.e: /opportunities/Yd6mya1w and /profile/carloscarvajal12) doesnt show anything ( Opportunity/user not found ) FIX!"

### Prompt 14: Search Functionality Debug
**Tool:** ChatGPT  
**Model:** GPT-4o  
**Prompt:** "Looking at the search functionality in the talent search page, I seem to note no requests sent upon keypress/click (meaning its non-functional); debug, and identify better UX"

### Prompt 15: Network Connectivity Analysis Issues
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Looking at the <Network Connectivity Analysis>, it looks totally unintuitive. Also <compare> doesn't work; returns 500 status code"

### Prompt 16: Homepage Auto-loading & Search Improvements
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "I want, upon landing on the homepage (/search) all profiles listed. Likewise for the jobs (/opportunities) - Improve the search functionality as it doesnt correlate much with the keyword I typed"

### Prompt 17: Documentation Request
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Regarding LLM/AI usage: Send all the prompts you used to build this. Send only the prompts, not their replies; For each prompt, specify each tool and model you've used; We suggest you document and commit them directly in the repository. Examples (and also to be populated) in the PROMPTS.md file"

### Prompt 18: Simplify Documentation
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "I only want the prompts; no technical implementation details needed - start from initial docker setup for the project, right to current pace where we are"

### Prompt 19: Add More Prompts
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "add more prompts, possibly in order of creation of relatable laravel project"

### Prompt 20: Fix Comparison Error and Demo Company Issue
**Tool:** Codebuff Assistant  
**Model:** Claude 3.5 Sonnet  
**Prompt:** "Comparison failed - Please try again!" Update PROMPTS.md correspondingly and for organization, why is "Demo Company" there? Shouldnt there be a unique org name?

