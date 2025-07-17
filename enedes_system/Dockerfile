FROM nginx:alpine

# Copiar apenas arquivos HTML
COPY index.html /usr/share/nginx/html/index.html
COPY login.html /usr/share/nginx/html/login.html

# Configuração do Nginx para SPA
RUN rm /etc/nginx/conf.d/default.conf
RUN echo 'server { \
    listen 80; \
    server_name _; \
    root /usr/share/nginx/html; \
    index index.html; \
    \
    location / { \
        try_files $uri $uri/ /index.html; \
    } \
    \
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ { \
        expires 1y; \
        add_header Cache-Control "public, immutable"; \
    } \
    \
    location = /favicon.ico { \
        log_not_found off; \
        access_log off; \
    } \
    \
    location ~ \.php$ { \
        return 404; \
    } \
}' > /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
