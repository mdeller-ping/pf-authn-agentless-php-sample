FROM php:7.0-apache
EXPOSE 80
RUN apt-get update
RUN apt-get install -y git vim
RUN git clone https://github.com/mdeller-ping/pf-authn-agentless-php-sample
RUN cp -r pf-authn-agentless-php-sample/idp/* /var/www/html
