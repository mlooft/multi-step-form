#!/usr/bin/env sh

# Install WordPress.
wp core install \
  --title="MSF Dev" \
  --admin_user="wordpress" \
  --admin_password="wordpress" \
  --admin_email="admin@example.com" \
  --url="http://localhost/" \
  --skip-email

# Update permalink structure.
wp option update permalink_structure "/%year%/%monthnum%/%postname%/" --skip-themes --skip-plugins

# Activate plugin.
wp plugin activate multi-step-form
