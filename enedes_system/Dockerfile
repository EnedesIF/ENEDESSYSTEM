FROM nginx:alpine

# Copiar arquivos da aplicação
COPY . /usr/share/nginx/html/

# Remover arquivo de configuração padrão
RUN rm /etc/nginx/conf.d/default.conf

# Criar configuração personalizada
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
    # Cache para arquivos estáticos \
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ { \
        expires 1y; \
        add_header Cache-Control "public, immutable"; \
    } \
}' > /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
