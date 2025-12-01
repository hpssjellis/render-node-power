# Use an official PHP image with the Apache web server
FROM php:8.2-apache

# ----------------------------------------------------
# 1. Install Python3 and its required build dependencies
# ----------------------------------------------------
# Update package lists and install python3
RUN apt-get update && \
    apt-get install -y \
        python3 \
        python3-pip \
    --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# ----------------------------------------------------
# 2. Install Node.js (and npm)
#    We use the NodeSource repositories for a current version
# ----------------------------------------------------
RUN apt-get update && \
    apt-get install -y curl gnupg && \
    # Add NodeSource GPG key and repository
    mkdir -p /etc/apt/keyrings && \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list > /dev/null

# Install Node.js (this includes npm)
RUN apt-get update && \
    apt-get install -y nodejs \
    --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# ----------------------------------------------------
# 3. Final setup for the PHP application
# ----------------------------------------------------

# Copy your application code into the web root directory of the container
COPY . /var/www/html/

# Expose the default Apache port (Render automatically maps this)
EXPOSE 80

# The base image already has the CMD set to run Apache.
# Note: Since the PHP version checks are now in index.php, they run on page load,
# not at startup, which is fine for getting the environment info!
