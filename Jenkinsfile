#!/usr/bin/env groovy

pipeline {
    environment {
       DIRS_TO_TEST= "tests src"
    }
    agent {
        label 'drupal8'
    }
    triggers {
        githubPush()
    }
    options { disableConcurrentBuilds() }
    stages {
        stage('Pull Request') {

           steps {
                sh '''
                    composer create-project drupal-composer/drupal-project:8.x-dev
                    composer create-project drupal-composer/drupal-project:8.x-dev drupal --stability dev --no-interaction
                    mkdir -p drupal/web/modules/${JOB_NAME%/*} && cp -a ${PWD##*/}* ${DIRS_TO_TEST} drupal/web/modules/${JOB_NAME%/*}
                    drupal/vendor/bin/phpunit -c drupal/web/core drupal/web/modules/${PWD##*/}/tests/
                '''
           }
        }
        stage('Static code analysis') {
            when {
                not { branch "PR-*" }
                not { branch "1.x" }
            }
            steps {
                sh '''
                    echo "This is not a PR"
                '''
            }
        }
    }
}

