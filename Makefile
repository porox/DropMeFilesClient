COMPOSER= composer
PHPCSFIXER= php -d memory_limit=1024m vendor/bin/php-cs-fixer

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

vendor:
	$(COMPOSER) instal -n

phpcs: vendor                                ## Lint PHP код
	$(PHPCSFIXER) fix --diff --dry-run --no-interaction -v ./src

phpcsfix: vendor                             ## Lint и исправление PHP кода
	$(PHPCSFIXER) fix ./src
