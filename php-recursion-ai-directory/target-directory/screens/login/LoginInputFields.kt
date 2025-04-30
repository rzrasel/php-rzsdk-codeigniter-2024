package com.rzrasel.wordquiz.presentation.screens.login

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.rzrasel.wordquiz.presentation.components.components.EmailTextField
import com.rzrasel.wordquiz.presentation.components.components.NormalButton
import com.rzrasel.wordquiz.presentation.components.components.PasswordTextField
import com.rzrasel.wordquiz.presentation.components.layout.HaveAnAccount
import com.rzrasel.wordquiz.presentation.components.layout.OrHorizontalRule
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state.UserLoginState
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun LoginInputFields(
    loginState: UserLoginState,
    onEmailOrMobileChange: (String)-> Unit,
    onPasswordChange: (String)-> Unit,
    onSubmit: ()-> Unit,
    onForgotPasswordClick: ()-> Unit,
    onSignUpClick: ()-> Unit,
    onSkipNowClick: ()-> Unit,
) {
    Column(modifier = Modifier.fillMaxWidth()) {
        // Email or Mobile Number
        EmailTextField(
            modifier = Modifier
                .fillMaxWidth()
                .padding(top = AppTheme.dimens.paddingLarge),
            value = loginState.emailOrMobile,
            onValueChange = onEmailOrMobileChange,
            label = "Email Address",
            //label = stringResource(id = R.string.login_email_or_phone_label),
            isError = loginState.errorState.emailOrMobileErrorState.hasError,
            errorText = loginState.errorState.emailOrMobileErrorState.errorMessageStringResource,
            //errorText = stringResource(id = loginState.errorState.emailOrMobileErrorState.errorMessageStringResource),
        )

        // Password
        PasswordTextField(
            modifier = Modifier
                .fillMaxWidth()
                .padding(top = AppTheme.dimens.paddingLarge),
            value = loginState.password,
            onValueChange = onPasswordChange,
            label = "Password",
            //label = stringResource(id = R.string.login_password_label),
            isError = loginState.errorState.passwordErrorState.hasError,
            errorText = loginState.errorState.passwordErrorState.errorMessageStringResource,
            //errorText = stringResource(id = loginState.errorState.passwordErrorState.errorMessageStringResource),
            imeAction = ImeAction.Done,
        )

        // Forgot Password
        Text(
            modifier = Modifier
                .padding(top = AppTheme.dimens.paddingSmall)
                .align(alignment = Alignment.End)
                .clickable {
                    onForgotPasswordClick.invoke()
                },
            text = "Forgot Password?",
            //text = stringResource(id = R.string.forgot_password),
            color = MaterialTheme.colorScheme.secondary,
            textAlign = TextAlign.End,
            style = MaterialTheme.typography.bodyMedium
        )

        // Login Submit Button
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement  =  Arrangement.End,
        ) {
            NormalButton(
                modifier = Modifier.padding(top = AppTheme.dimens.paddingLarge),
                text = "Sign In",
                //text = stringResource(id = R.string.login_button_text),
                onClick = onSubmit,
            )
        }

        OrHorizontalRule()

        // Sign Up
        Column(
            modifier = Modifier
                .fillMaxWidth(),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.End,
        ) {
            HaveAnAccount(
                leftLevel = "Don't have an account?",
                rightLevel = "Sign Up",
                onSignUpClick
            )
        }

        // Skip
        Column(
            modifier = Modifier
                .fillMaxWidth(),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.End,
        ) {
            Text(
                modifier = Modifier
                    .padding(horizontal = 6.dp, vertical = 4.dp)
                    .clickable {
                        onSkipNowClick()
                    },
                text = "Skip Now",
                fontSize = 14.sp,
                fontWeight = FontWeight.Bold,
            )
        }
    }
}