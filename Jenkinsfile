#!/usr/bin/env groovy

pipeline {
    agent { label 'drupal8' }
    triggers { githubPush() }
    options { disableConcurrentBuilds() }
    stages {

        stage('Static code analysis') {
            steps {
                withSonarQubeEnv('lighting-prototype') {
                    sh "sonar-scanner -Dsonar.analysis.mode=preview"
                }
            }
        }
    }
}

