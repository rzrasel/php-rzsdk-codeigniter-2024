package com.rzrasel.wordquiz.presentation.screens.state

import com.rzrasel.wordquiz.presentation.state.ErrorState

/**
 * Login State holding ui input values
 */
data class LoginStateOld(
    val emailOrMobile: String = "",
    val password: String = "",
    val errorState: LoginErrorStateOld = LoginErrorStateOld(),
    val isLoading: Boolean = false,
    val isLoginSuccessful: Boolean = false,
)

/**
 * Error state in login holding respective
 * text field validation errors
 */
data class LoginErrorStateOld(
    val emailOrMobileErrorState: ErrorState = ErrorState(),
    val passwordErrorState: ErrorState = ErrorState(),
)