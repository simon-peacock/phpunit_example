#!/usr/bin/env groovy

pipeline {
    agent { label 'drupal8' }
    triggers { githubPush() }
    options { disableConcurrentBuilds() }
    stages {

        stage('Static code analysis') {
            when {
                not { branch "PR-*" }
                not { branch "1.x" }
            }
            steps {
                withSonarQubeEnv('lightning') {
                    sh "sonar-scanner -Dsonar.analysis.mode=preview"
                }
            }
        }
    }
}

