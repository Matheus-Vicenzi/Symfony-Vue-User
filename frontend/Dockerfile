FROM node:20-slim AS build

WORKDIR /app

RUN useradd -m nodeuser

RUN chown -R nodeuser:nodeuser /app

USER nodeuser

COPY --chown=nodeuser:nodeuser package*.json ./

RUN npm install

COPY --chown=nodeuser:nodeuser . .

RUN npm run build

FROM nginx:alpine

COPY ./nginx.conf /etc/nginx/conf.d/default.conf

COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
