<?php
namespace DominionEnterprises\Chef;

class ChefEc2
{
    /**
     * @var string The base command for knife.
     */
    private $_baseKnifeCommand;

    /**
     * @var string The url for the chef API.
     */
    private $_chefServerUrl;

    /**
     * @var array The credentials needed to interact with knife-ec2.
     */
    private $_credentials;

    /**
     * Initialize the wrapper around knife-ec2.
     *
     * @param string $baseKnifeCommand The base command for knife.  could be 'knife' if knife is already in the path.  If using bundler to
     *     install knife-ec2, you could do something like 'BUNDLE_GEMFILE=/path/to/Gemfile bundle exec knife'.
     * @param string $chefServerUrl The url for the chef API.
     * @param array $credentials The credentials needed to interact with knife-ec2.  Includes awsAccessKeyId and awsSecretAccessKey.
     */
    public function __construct($baseKnifeCommand, $chefServerUrl, array $credentials)
    {
        $this->_baseKnifeCommand = $baseKnifeCommand;
        $this->_chefServerUrl = $chefServerUrl;
        $this->_credentials = $credentials;
    }

    /**
     * Instantiate a new server.
     *
     * @param string $region The AWS region the server is in.
     * @param string $ami The region-specific AMI to use.
     * @param string $flavor The flavor of server to create, e.g. t1.micro.
     * @param array $runList The roles/recipes for the chef run list.
     * @param string $progressFile A file path to store the server output in.
     * @param array $options Additional options for knife-ec2.  For example: ['-groups' => 'foo']
     * @param array $tags Tags to set for the server.
     * @param string $chefVersion The chef version to use to bootstrap the server.
     *
     * @return void
     */
    public function createServer(
        $region,
        $ami,
        $flavor,
        array $runList,
        $progressFile,
        array $options = [],
        array $tags = [],
        $chefVersion = '11.8.0'
    )
    {
        $tagList = [];
        foreach ($tags as $key => $value) {
            $tagList[] = "{$key}={$value}";
        }

        $fullOptions = [
            '--region' => $region,
            '--image' => $ami,
            '--flavor' => $flavor,
            '--run-list' => implode(',', $runList),
            '--tags' => implode(',', $tagList),
            '--bootstrap-version' => $chefVersion,
        ];

        $fullOptions += $options;
        $fullOptions += $this->_awsCredentialParameters();
        $fullOptions += $this->_chefClientParameters();
        $fullOptions += $this->_ec2SshParameters();

        $command = \Hiatus\addArguments("{$this->_baseKnifeCommand} ec2 server create", $fullOptions);
        \Hiatus\execX("{$command} >" . escapeshellarg($progressFile) . ' 2>&1 &');
    }

    /**
     * Runs chef client on the servers that match the query storing command output in the given file.
     *
     * @param string $query The chef query to specify what servers to update.
     * @param string $progressFile The filename to send the knife output to.
     * @param array $options Additional options for knife ssh.  For example: ['-groups' => 'foo']
     * @return void
     */
    public function updateServers($query, $progressFile, array $options = [])
    {
        $instanceIdUrl = 'http://169.254.169.254/latest/meta-data/instance-id';
        $options = array_merge(
            $options,
            $this->_chefClientParameters(),
            $this->_ec2SshParameters(),
            [$query, "sudo chef-client --json-attributes /etc/chef/first-boot.json -N `curl {$instanceIdUrl}`"]
        );
        $command = \Hiatus\addArguments("{$this->_baseKnifeCommand} ssh", $options);
        \Hiatus\execX("{$command} >" . escapeshellarg($progressFile) . ' 2>&1 &');
    }

    /**
     * Get the knife-ec2 parameters for basic AWS API credentials.
     *
     * @return array The parameters to access AWS via knife-ec2.
     */
    private function _awsCredentialParameters()
    {
        return [
            '--aws-access-key-id' => $this->_credentials['awsAccessKeyId'],
            '--aws-secret-access-key' => $this->_credentials['awsSecretAccessKey'],
        ];
    }

    /**
     * Get the chef client parameters.
     *
     * @return array The parameters to access the chef API via knife.
     */
    private function _chefClientParameters()
    {
        return [
            '--server-url' => $this->_chefServerUrl,
            '--user' => $this->_credentials['chefClientName'],
            '--key' => $this->_credentials['chefClientKey'],
        ];
    }

    /**
     * Get the EC2 SSH parameters.
     *
     * @return array The parameters to access the EC2 ssh servers
     */
    private function _ec2SshParameters()
    {
        return ['--ssh-user' => $this->_credentials['ec2SshUser'], '--identity-file' => $this->_credentials['ec2SshKey']];
    }
}
