// Reference the GitLab connection name from your Jenkins Global configuration (http://JENKINS_URL/configure, GitLab section)
properties([gitLabConnection('sfponline')])

node {
    stage "checkout"
    checkout scm

    stage "build"
    gitlabCommitStatus("build") {
        // your build steps
        sh 'php --version'
        sh 'php composer.pchar install'
    }

    stage "test"
    gitlabCommitStatus("test") {
        // your test steps
        sh 'php --version'
        sh 'php composer.pchar install'
    }
}