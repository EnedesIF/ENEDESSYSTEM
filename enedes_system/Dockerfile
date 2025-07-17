FROM nginx:alpine

# Copiar arquivos do projeto
COPY . /usr/share/nginx/html/

# Configurar Nginx para SPA (Single Page Application)
RUN rm /etc/nginx/conf.d/default.conf

# Criar configuração otimizada do Nginx
RUN echo 'server { \
    listen 80; \
    server_name _; \
    root /usr/share/nginx/html; \
    index index.html index.php; \
    \
    # Configuração para SPA \
    location / { \
        try_files $uri $uri/ /index.html; \
    } \
    \
    # Cache para arquivos estáticos \
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ { \
        expires 1y; \
        add_header Cache-Control "public, immutable"; \
    } \
    \
    # Desabilitar logs de arquivos não encontrados para favicon \
    location = /favicon.ico { \
        log_not_found off; \
        access_log off; \
    } \
}' > /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
