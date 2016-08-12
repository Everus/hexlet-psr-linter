install:
	composer install

autoload:
	composer dump-autoload

lint:
	composer exec 'phpcs --standard=PSR2 src tests --ignore=tests/Fixtures/*'

self-lint:
	./psrlint src

test:
	composer exec 'phpunit --color tests'

cover:
	composer exec 'phpunit --coverage-clover build/logs/clover.xml'
