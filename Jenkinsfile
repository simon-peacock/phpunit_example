#!/usr/bin/env groovy

pipeline {
    agent { label 'dennis-php' }
    triggers { githubPush() }
    options { disableConcurrentBuilds() }
    stages {
        stage('Unit Test') {
           when {
               expression { branch "PR-*" }
           }
           steps {
                sh '''
                    composer create-project drupal-composer/drupal-project:8.x-dev drupal --stability dev --no-interaction
                    mkdir -p drupal/web/modules/${JOB_NAME%/*} && rsync -av --progress . drupal/web/modules/${JOB_NAME%/*} --exclude drupal
                    mkdir coverage
                    drupal/vendor/bin/phpunit -c drupal/web/core drupal/web/modules/${JOB_NAME%/*}/tests/ --coverage-clover $WORKSPACE/reports/coverage.xml --log-junit $WORKSPACE/reports/phpunit.xml
                '''
           }
        }
        stage('Static Code Analysis') {
           when {
               expression { branch "PR-*" }
           }
           steps {
               withSonarQubeEnv('lighting-prototype') {
                   sh "sonar-scanner "
               }
           }
        }
    }
}
