FROM nginx:alpine

# Copiar APENAS os arquivos HTML necessários (SEM pasta api/)
COPY index.html /usr/share/nginx/html/
COPY login.html /usr/share/nginx/html/

# Remover configuração padrão
RUN rm /etc/nginx/conf.d/default.conf

# Criar configuração otimizada do Nginx (SEM suporte PHP)
RUN echo 'server { \
    listen 80; \
    server_name _; \
    root /usr/share/nginx/html; \
    index index.html; \
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
    \
    # Bloquear acesso a arquivos PHP \
    location ~ \.php$ { \
        return 404; \
    } \
}' > /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
