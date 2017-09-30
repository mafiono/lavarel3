pipeline {
    agent any
    stages {
        stage('build') {
            steps {
                sh 'php --version'
                sh 'php composer.pchar install'
            }
        }
    }
}