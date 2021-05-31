import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link, Redirect } from 'react-router-dom';
import ReeValidate from 'ree-validate';
import classNames from 'classnames';
import AuthService from '../services';

class Login extends Component {
  constructor() {
    super();

    this.validator = new ReeValidate({
      email: 'required|email',
      password: 'required|min:6',
    });

    this.state = {
      loading: false,
      email: '',
      password: '',
      errors: {},
      response: {
        error: false,
        message: '',
      },
    };
  }

  handleChange = (e) => {
    const { name, value } = e.target;
    this.setState({ [name]: value });

    // If a field has a validation error, we'll clear it when corrected.
    const { errors } = this.state;
    if (name in errors) {
      const validation = this.validator.errors;
      this.validator.validate(name, value).then(() => {
        if (!validation.has(name)) {
          delete errors[name];
          this.setState({ errors });
        }
      });
    }
  };

  handleBlur = (e) => {
    const { name, value } = e.target;

    // Avoid validation until input has a value.
    if (value === '') {
      return;
    }

    const validation = this.validator.errors;
    this.validator.validate(name, value).then(() => {
      if (validation.has(name)) {
        const { errors } = this.state;
        errors[name] = validation.first(name);
        this.setState({ errors });
      }
    });
  };

  handleSubmit = (e) => {
    e.preventDefault();
    const { email, password } = this.state;
    const credentials = {
      email,
      password,
    };

    // Set response state back to default.
    this.setState({ response: { error: false, message: '' } });

    this.validator.validateAll(credentials).then((success) => {
      if (success) {
        this.setState({ loading: true });
        this.submit(credentials);
      }
    });
  };

  submit(credentials) {
    const { dispatch } = this.props;
    dispatch(AuthService.login(credentials)).catch((err) => {
      this.loginForm.reset();
      const errors = Object.values(err.errors);
      errors.join(' ');
      const response = {
        error: true,
        message: errors,
      };
      this.setState({ response });
      this.setState({ loading: false });
    });
  }

  render() {
    // If user is already authenticated we redirect to entry location.
    const { location: state } = this.props;
    const { from } = state || { from: { pathname: '/' } };
    const { isAuthenticated } = this.props;
    if (isAuthenticated) {
      return <Redirect to={from} />;
    }

    const { response, errors, loading } = this.state;

    return (
      <div>
        <div className="d-flex flex-column flex-row align-content-center py-5">
          <div className="container">
            <div className="row">
              <div className="section-login col-lg-6 mx-auto">
                <h4>Log in to the App</h4>

                <div className="card-login card mb-3">
                  <div className="card-body">
                    {response.error && (
                      <div
                        className="alert alert-danger text-center"
                        role="alert"
                      >
                        Credentials were incorrect. Try again!
                      </div>
                    )}

                    <form
                      className="form-horizontal"
                      method="POST"
                      onSubmit={this.handleSubmit}
                      ref={(el) => {
                        this.loginForm = el;
                      }}
                    >
                      <div className="form-group mb-2">
                      <label htmlFor="email">Email Address</label>
                        <input
                          id="email"
                          type="email"
                          name="email"
                          className={classNames('form-control', {
                            'is-invalid': 'email' in errors,
                          })}
                          placeholder="Enter email"
                          required
                          onChange={this.handleChange}
                          onBlur={this.handleBlur}
                          disabled={loading}
                        />

                        {'email' in errors && (
                          <div className="invalid-feedback">{errors.email}</div>
                        )}
                      </div>

                      <div className="form-group">
                        <label htmlFor="password">Password</label>
                        <input
                          id="password"
                          type="password"
                          className={classNames('form-control', {
                            'is-invalid': 'password' in errors,
                          })}
                          name="password"
                          placeholder="Enter password"
                          required
                          onChange={this.handleChange}
                          onBlur={this.handleBlur}
                          disabled={loading}
                        />
                        {'password' in errors && (
                          <div className="invalid-feedback">
                            {errors.password}
                          </div>
                        )}
                      </div>

                      <div className="form-group text-center">
                        <button
                          type="submit"
                          className={classNames('btn btn-primary', {
                            'btn-loading': loading,
                          })}
                        >
                          Sign In
                        </button>
                      </div>

                      <div className="login-invite-text text-center">
                        No account?
                        {' '}
                        <Link to="/register" href="/register">
                          Register
                        </Link>
                        .
                      </div>
                    </form>
                  </div>
                </div>

                <div className="password-reset-link text-center">
                  <Link to="/forgot-password" href="/forgot-password">
                    Forgot Your Password?
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

Login.defaultProps = {
  location: {
    state: {
      pathname: '/',
    },
  },
};

Login.propTypes = {
  dispatch: PropTypes.func.isRequired,
  isAuthenticated: PropTypes.bool.isRequired,
  location: PropTypes.shape({
    state: {
      pathname: PropTypes.string,
    },
  }),
};

const mapStateToProps = (state) => ({
  isAuthenticated: state.Auth.isAuthenticated,
});

export default connect(mapStateToProps)(Login);
