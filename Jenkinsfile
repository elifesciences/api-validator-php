elifeLibrary {
    stage 'Checkout'
    checkout scm

    stage "Tests"
    sh "./project_tests.sh || echo TESTS FAILED"
    elifeTestArtifact "build/phpunit.xml"
    elifeVerifyJunitXml "build/phpunit.xml"
    elifeTestArtifact "build/phpspec.xml"
    elifeVerifyJunitXml "build/phpspec.xml"
}
