module.exports = {
  extends: 'airbnb',
  parser: 'babel-eslint',
  rules: {
    'react/jsx-filename-extension': [1, { extensions: ['.js', '.jsx'] }],
    'jsx-a11y/label-has-for': [2, {
      components: ['Label'],
      required: {
        every: ['id'],
      },
      allowChildren: false,
    }],
    "react/jsx-props-no-spreading": "off",
  },
  env: {
    browser: true,
  },
};
