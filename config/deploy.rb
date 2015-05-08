# Main settings
# --------------------------------------------------

set :application,    'bootstrap-yii2'
set :repo_url,       ''
set :scm,            :git
set :deploy_via,     :remote_cache
set :keep_releases,  5

set :linked_dirs,  %w{web/uploads web/assets runtime vendor node_modules config/local}

# Tasks
# --------------------------------------------------

namespace :deploy do

  desc 'Prepare application'
  task :prepare do
    on roles(:all) do
        within release_path do
          execute :composer, "install"
          execute :npm, "install"
          execute :bower, "install"
          execute :npm, "run build"
          execute :php, "yii migrate/up --interactive"
          execute :php, "yii rbac/init"
        end
    end
  end

  desc 'Enable maintenance mode'
  task :lock do
    on roles(:all) do
      execute "cd #{release_path} && php yii maintenance/on"
    end
  end

  desc 'Disable maintenance mode'
  task :unlock do
    on roles(:all) do
      execute "cd #{release_path} && php yii maintenance/off"
    end
  end

  # Deploy flow
  # --------------------------------------------------

  after :updated,    'deploy:prepare'
  after :finishing,  'deploy:cleanup'

end
