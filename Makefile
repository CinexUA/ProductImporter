app_container = pi-php
run:
	docker-compose up
inspect_app:
	docker inspect $(app_container)
enter_app:
	docker exec -ti $(app_container) bash
stats:
	docker stats
takes_memory:
	docker ps -s
inspect_process:
	docker-compose ps
ide_helper_regen:
	docker exec $(app_container) sh -c "php artisan ide-helper:generate && \
	php artisan ide-helper:meta && php artisan ide-helper:models --nowrite"
reset_app_perm:
	sudo chown -Rf ${USER}:${USER} ./src/* && \
	docker exec $(app_container) sh -c "chmod -Rf 777 storage && \
	chmod -Rf 777 bootstrap/cache/"
reset_logs_perm:
	sudo chown -Rf ${USER}:${USER} ./logs/*
copy_fpm_settings:
	sudo docker cp $(app_container):"/usr/local/etc/php-fpm.d/" "/home"
