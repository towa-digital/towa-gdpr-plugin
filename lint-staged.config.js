module.exports = {
  '*.js': ['cross-env NODE_ENV=development node_modules/eslint/bin/eslint.js', 'git add'],
  '*.php': ['composer phpcbf', 'git add']
}
