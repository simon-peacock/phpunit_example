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
                    mkdir -p drupal/web/modules/${JOB_NAME%/*} && rsync -av --progress . drupal/web/modules/${JOB_NAME%/*} --exclude drupal
                    drupal/vendor/bin/phpunit -c drupal/web/core drupal/web/modules/${JOB_NAME%/*}/tests/
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

