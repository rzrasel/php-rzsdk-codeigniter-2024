package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state

import com.rzrasel.wordquiz.presentation.state.ErrorState

/**
 * Login State holding ui input values
 */
data class UserLoginState(
    var emailOrMobile: String = "mrashid@gmail.com",
    var password: String = "123",
    val errorState: UserLoginErrorState = UserLoginErrorState(),
    val isLoading: Boolean = false,
    val isLoginSuccessful: Boolean = false,
)

/**
 * Error state in login holding respective
 * text field validation errors
 */
data class UserLoginErrorState(
    val emailOrMobileErrorState: ErrorState = ErrorState(),
    val passwordErrorState: ErrorState = ErrorState(),
)