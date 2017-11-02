// Reference the GitLab connection name from your Jenkins Global configuration (http://JENKINS_URL/configure, GitLab section)
properties([gitLabConnection('sfponline')])

node {
    stage "checkout"
    checkout scm

    stage "build"
    gitlabCommitStatus("build") {
        // your build steps
        sh 'php --version'
        sh 'php composer.phar install --no-progress'
        sh 'npm install --progress=false'
        sh 'env'
    }

    stage "test"
    gitlabCommitStatus("test") {
        // your test steps
        sh 'php --version'
        sh 'ls -la'
        sh 'npm -v'
        sh 'php composer.phar install --no-progress'
    }
}