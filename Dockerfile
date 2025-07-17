FROM nginx:alpine
COPY index.html /usr/share/nginx/html/
COPY login.html /usr/share/nginx/html/
RUN rm /etc/nginx/conf.d/default.conf
RUN echo 'server { listen 80; server_name _; root /usr/share/nginx/html; index index.html; location / { try_files $uri $uri/ /index.html; } location ~ \.php$ { return 404; } }' > /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
