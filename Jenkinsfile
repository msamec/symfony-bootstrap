#!/usr/bin/env groovy

node('master') {
    try {
        stage('build') {
            checkout scm
            sh "composer install"
        }

        stage('test') {
            sh "echo 'test will go here'"
        }
    } catch (error) {
        thrown error
    } finally {

    }
}
