server {
    listen      {{port}};
    server_name *.{{server}};

    set         $environment {{environment}};

    error_log   {{root}}/etc/logs/error.log;
    access_log  {{root}}/etc/logs/access.log;
    root        {{root}}/public;
    charset     utf-8;
    index       index.php;

    error_page 500 501 502 503 504  /fatal.html;

    client_body_timeout          60;
    send_timeout                 60;
    client_max_body_size         20m;

    fastcgi_connect_timeout      60;
    fastcgi_send_timeout         180;
    fastcgi_read_timeout         180;
    fastcgi_buffer_size          128k;
    fastcgi_buffers              4 256k;
    fastcgi_busy_buffers_size    256k;
    fastcgi_temp_file_write_size 256k;
    fastcgi_intercept_errors     off;

    set $subdomain "www";
    if ($host ~* ^([-a-z0-9\.]+)\.{{regexp_server}}$) {
        set $subdomain $1;
    }

    location / {
        default_type text/html;

        error_page 404 405 418 = @fallback;

        if ($environment = 'production') {
            set $memcached_key "$cookie_session|$host|$request_uri";
            memcached_pass {{memcached}};
        }

        if ($environment != 'production') {
            return 418;
        }
    }

    location @fallback {
        include fastcgi_params;

        fastcgi_pass  {{php-fpm}};
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME {{root}}/public/index.php;
        fastcgi_param SCRIPT_NAME     index.php;
        fastcgi_param PATH_INFO       $fastcgi_path_info;

        set $custom_uri $uri;
        if ($subdomain != "www") {
            set $custom_uri /$subdomain$uri;
        }
        # |----- Bug with subdomains and thumbnails serve
        # V
        # fastcgi_param REQUEST_URI $custom_uri;
        fastcgi_param DOCUMENT_URI $custom_uri;
        fastcgi_param KOHANA_ENV   $environment;
    }

    location ~* ^/thumbnails/ {
        if (!-e $request_filename ) {
            rewrite ^(.*)$ /index.php?$1? last;
        }
    }

    location = /fatal.html {
        root {{root}}/public/errors;
    }

    location ~* ^/(favicon.ico|robots.txt)$ {
        root {{root}}/public;
    }

    location ~* ^/uploads/.+?\.flv$ {
        expires 1y;
        add_header Cache-Control public;
        root {{root}}/public;

        flv;
    }

    location ~* ^/(i|thumbnails|uploads|css|js|cache)/.+$ {
        expires 1y;
        add_header Cache-Control public;
        root {{root}}/public;
    }
}

server {
    server_name {{server}};

    location / {
        rewrite ^(.*)$ http://www.{{server}}$1 permanent;
    }
}
