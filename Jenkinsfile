#!/usr/bin/env groovy

pipeline {
    agent { label 'drupal8' }
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
                    drupal/vendor/bin/phpunit -c drupal/web/core drupal/web/modules/${JOB_NAME%/*}/tests/ --coverage-clover $WORKSPACE/reports/coverage.xml --log-junit $WORKSPACE/reports/phpunit.xml
                '''

                step([
                  $class: 'CloverPublisher',
                  cloverReportDir: 'target/site',
                  cloverReportFileName: 'clover.xml',
                  healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80], // optional, default is: method=70, conditional=80, statement=80
                  unhealthyTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50], // optional, default is none
                  failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]     // optional, default is none
                ])
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
