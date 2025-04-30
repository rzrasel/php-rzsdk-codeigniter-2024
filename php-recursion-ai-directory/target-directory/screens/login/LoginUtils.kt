package com.rzrasel.wordquiz.presentation.screens.login

import com.rzrasel.wordquiz.R
import com.rzrasel.wordquiz.presentation.state.ErrorState

val emailOrMobileEmptyErrorState = ErrorState(
    hasError = true,
    errorMessageStringResource = "Please enter your email or mobile number"
)

val passwordEmptyErrorState = ErrorState(
    hasError = true,
    errorMessageStringResource = "Please enter your password"
)