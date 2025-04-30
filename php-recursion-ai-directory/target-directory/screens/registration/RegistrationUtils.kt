package com.rzrasel.wordquiz.presentation.screens.registration

import com.rzrasel.wordquiz.presentation.state.ErrorState

val emailEmptyErrorState = ErrorState(
    hasError = true,
    errorMessageStringResource = "Please enter your email address"
)

val mobileNumberEmptyErrorState = ErrorState(
    hasError = true,
    errorMessageStringResource = "Please enter your mobile number"
)

val passwordEmptyErrorState = ErrorState(
    hasError = true,
    errorMessageStringResource = "Please enter your password"
)

val confirmPasswordEmptyErrorState = ErrorState(
    hasError = true,
    errorMessageStringResource = "Please confirm your password"
)

val passwordMismatchErrorState = ErrorState(
    hasError = true,
    errorMessageStringResource = "Please enter the same password here as above"
)