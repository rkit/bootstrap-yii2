set :stage, :production

set :branch,    'master'
set :deploy_to, ''
set :domain,    ''
set :url,	    'http://' + fetch(:domain)

server fetch(:domain), user: '', roles: %w{web app},
ssh_options: 
{
    forward_agent: true,
}