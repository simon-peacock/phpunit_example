#!/usr/bin/env groovy

pipeline {
    agent { label 'drupal8' }
    triggers { githubPush() }
    options { disableConcurrentBuilds() }
    stages {
        stage('Pull Request') {
           when {
               expression { branch "PR-*" }
               expression { branch "1.x" }
           }
           steps {
                sh '''
                    echo sh(returnStdout: true, script: 'env')
                '''

                //composer create-project drupal-composer/drupal-project:8.x-dev drupal --stability dev --no-interaction
                //mkdir -p drupal/web/modules/${JOB_NAME%/*} && rsync -av --progress . drupal/web/modules/${JOB_NAME%/*} --exclude drupal
                //drupal/vendor/bin/phpunit -c drupal/web/core drupal/web/modules/${JOB_NAME%/*}/tests/
           }
        }
    }
}

