FROM debian:jessie
MAINTAINER Gunter Thomas <gunter.thomas@digitalrepublic.com>

# for unattended installation
ENV DEBIAN_FRONTEND noninteractive

# install software
RUN apt-get update -qq && apt-get install --no-install-recommends -y --force-yes \
        apache2 \
        libapache2-mod-php5 \
        php5-cli \
        php5-dev \
        php5-intl \
        php5-mysql \
        supervisor \
        && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Apache + PHP requires preforking Apache for best results
RUN a2enmod rewrite && \
    a2enmod php5

CMD ["/var/www/docker/apache2/apache_user.sh"]
CMD ["chmod 777 -R /var/www/storage/"]

# add vhost configuration
COPY apache2/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
CMD ["/usr/bin/supervisord"]
