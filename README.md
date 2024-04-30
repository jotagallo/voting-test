# Voting test - code challenge

Voting system for code challenge test using Drupal 10.

## About the project

This project is a simple voting system simulator using a Drupal custom module to pack all the requirements for code and config. System's native RESTful API paths can be used for external access of the content.

## How to run

- Use <strong><i>./start.sh</i></strong> to install containers, dependencies and get the site ready to run. 

- Follow the instructions to setup the database. The <i>docker-compose.yml</i> and <i>settings.php</i> files holds defaults credentials but you can change them by taste or if needed.

- Use <strong><i>./stop.sh</i></strong> to shutdown eveything.

## Known issues

- This projects relies on simplicity, so some parametrizations (as .env file) were suppressed for time saving. The code have some comments about that whenever is relevant.
- No custom frontend asset (JS or CSS) was added, althought having the voting working on Ajax would be a nice to have.
- For some reason, PHP-GD module is not processing JPEG files for image presets. Maybe some version incompatibility, i could't have time to dig into a non-requirement and non-blocker issue. Using PNG is just fine by now.