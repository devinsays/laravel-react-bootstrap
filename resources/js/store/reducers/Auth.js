import * as ActionTypes from '../action-types';
import Http from '../../Http';

const defaultUser = {
  id: null,
  name: null,
  email: null,
};

const initialState = {
  isAuthenticated: false,
  user: defaultUser,
};

const authLogin = (state, payload) => {
  const { access_token: AccessToken, user } = payload;
  localStorage.setItem('access_token', AccessToken);
  localStorage.setItem('user', JSON.stringify(user));
  Http.defaults.headers.common.Authorization = `Bearer ${AccessToken}`;
  const stateObj = {
    ...state,
    isAuthenticated: true,
    user,
  };
  return stateObj;
};

const checkAuth = (state) => {
  const stateObj = {
    ...state,
    isAuthenticated: !!localStorage.getItem('access_token'),
    user: JSON.parse(localStorage.getItem('user')),
  };
  if (state.isAuthenticated) {
    Http.defaults.headers.common.Authorization = `Bearer ${localStorage.getItem(
      'access_token',
    )}`;
  }
  return stateObj;
};

const logout = (state) => {
  localStorage.removeItem('access_token');
  localStorage.removeItem('user');
  const stateObj = {
    ...state,
    isAuthenticated: false,
    user: defaultUser,
  };
  return stateObj;
};

const Auth = (state = initialState, { type, payload = null }) => {
  switch (type) {
    case ActionTypes.AUTH_LOGIN:
      return authLogin(state, payload);
    case ActionTypes.AUTH_CHECK:
      return checkAuth(state);
    case ActionTypes.AUTH_LOGOUT:
      return logout(state);
    default:
      return state;
  }
};

export default Auth;
