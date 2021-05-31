import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import ReeValidate from 'ree-validate';
import classNames from 'classnames';
import AuthService from '../services';

class ResetPassword extends Component {
  constructor(props) {
    super(props);

    // @TODO Password confirmation validation.
    this.validator = new ReeValidate({
      password: 'required|min:6',
      password_confirmation: 'required|min:6',
      id: 'required',
      token: 'required',
    });

    this.state = {
      loading: false,
      id: this.getResetId(),
      token: this.getResetToken(),
      password: '',
      password_confirmation: '',
      errors: {},
      response: {
        error: false,
        message: '',
      },
    };
  }

  getResetId() {
    const params = new URLSearchParams(this.props.location.search);
    if (params.has('id')) {
      return params.get('id');
    }
    return '';
  }

  getResetToken() {
    const params = new URLSearchParams(this.props.location.search);
    if (params.has('token')) {
      return params.get('token');
    }
    return '';
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
    const validation = this.validator.errors;

    // Avoid validation until input has a value.
    if (value === '') {
      return;
    }

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
    const credentials = {
      id: this.state.id,
      token: this.state.token,
      password: this.state.password,
      password_confirmation: this.state.password_confirmation,
    };

    this.setState({ loading: true });

    this.props
      .dispatch(AuthService.updatePassword(credentials))
      .then((res) => {
        this.passwordResetForm.reset();
        const response = {
          error: false,
          message: res.message,
        };
        this.setState({ loading: false, success: true, response });
      })
      .catch((err) => {
        this.passwordResetForm.reset();
        const errors = Object.values(err.errors);
        errors.join(' ');
        const response = {
          error: true,
          message: errors,
        };
        this.setState({ response });
        this.setState({ loading: false });
      });
  };

  render() {
    // If user is already authenticated we redirect to entry location.
    const { from } = this.props.location.state || { from: { pathname: '/' } };
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
                <h4>Password Reset</h4>

                <div className="card-login card mb-3">
                  <div className="card-body">
                    {this.state.success && (
                      <div
                        className="alert alert-success text-center"
                        role="alert"
                      >
                        Your password has been reset!
                      </div>
                    )}

                    {response.error && (
                      <div
                        className="alert alert-danger text-center"
                        role="alert"
                      >
                        {response.message}
                      </div>
                    )}

                    {!this.state.success && (
                      <form
                        className="form-horizontal"
                        method="POST"
                        onSubmit={this.handleSubmit}
                        ref={(el) => {
                          this.passwordResetForm = el;
                        }}
                      >
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
                          />
                          {'password' in errors && (
                            <div className="invalid-feedback">
                              {errors.password}
                            </div>
                          )}
                        </div>

                        <div className="form-group">
                          <label htmlFor="password_confirmation">
                            Password Confirmation
                          </label>
                          <input
                            id="password_confirmation"
                            type="password"
                            className={classNames('form-control', {
                              'is-invalid': 'password_confirmation' in errors,
                            })}
                            name="password_confirmation"
                            placeholder="Confirm password"
                            required
                            onChange={this.handleChange}
                            onBlur={this.handleBlur}
                          />
                          {'password_confirmation' in errors && (
                            <div className="invalid-feedback">
                              {errors.password_confirmation}
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
                            Reset Password
                          </button>
                        </div>
                      </form>
                    )}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

ResetPassword.propTypes = {
  dispatch: PropTypes.func.isRequired,
  isAuthenticated: PropTypes.bool.isRequired,
};

const mapStateToProps = (state) => ({
  isAuthenticated: state.Auth.isAuthenticated,
});

export default connect(mapStateToProps)(ResetPassword);
