package com.rzrasel.wordquiz.presentation.screens.registration

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.unit.dp
import com.rzrasel.wordquiz.presentation.components.components.EmailTextField
import com.rzrasel.wordquiz.presentation.components.components.NormalButton
import com.rzrasel.wordquiz.presentation.components.components.PasswordTextField
import com.rzrasel.wordquiz.presentation.components.layout.HaveAnAccount
import com.rzrasel.wordquiz.presentation.components.layout.OrHorizontalRule
import com.rzrasel.wordquiz.presentation.screens.state.RegistrationState
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun RegistrationInputFields(
    registrationState: RegistrationState,
    onEmailAddressChange: (String)-> Unit,
    onPasswordChange: (String)-> Unit,
    onConfirmPasswordChange: (String)-> Unit,
    onSubmit: ()-> Unit,
    onSignInClick: ()-> Unit,
) {
    Column(modifier = Modifier.fillMaxWidth()) {
        // Email or Mobile Number
        EmailTextField(
            modifier = Modifier
                .fillMaxWidth()
                .padding(top = AppTheme.dimens.paddingLarge),
            value = registrationState.emailAddress,
            onValueChange = onEmailAddressChange,
            label = "Email Address",
            //label = stringResource(id = R.string.login_email_or_phone_label),
            isError = registrationState.errorState.emailAddressErrorState.hasError,
            errorText = registrationState.errorState.emailAddressErrorState.errorMessageStringResource,
            //errorText = stringResource(id = loginState.errorState.emailOrMobileErrorState.errorMessageStringResource),
        )

        // Password
        PasswordTextField(
            modifier = Modifier
                .fillMaxWidth()
                .padding(top = AppTheme.dimens.paddingLarge),
            value = registrationState.password,
            onValueChange = onPasswordChange,
            label = "Password",
            //label = stringResource(id = R.string.login_password_label),
            isError = registrationState.errorState.passwordErrorState.hasError,
            errorText = registrationState.errorState.passwordErrorState.errorMessageStringResource,
            //errorText = stringResource(id = loginState.errorState.passwordErrorState.errorMessageStringResource),
            imeAction = ImeAction.Done,
        )

        // Password
        PasswordTextField(
            modifier = Modifier
                .fillMaxWidth()
                .padding(top = AppTheme.dimens.paddingLarge),
            value = registrationState.confirmPassword,
            onValueChange = onConfirmPasswordChange,
            label = "Confirm Password",
            //label = stringResource(id = R.string.login_password_label),
            isError = registrationState.errorState.confirmPasswordErrorState.hasError,
            errorText = registrationState.errorState.confirmPasswordErrorState.errorMessageStringResource,
            //errorText = stringResource(id = loginState.errorState.passwordErrorState.errorMessageStringResource),
            imeAction = ImeAction.Done,
        )

        // Registration Submit Button
        Row(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement  =  Arrangement.End,
        ) {
            NormalButton(
                modifier = Modifier.padding(top = AppTheme.dimens.paddingLarge),
                text = "Sign Up",
                //text = stringResource(id = R.string.login_button_text),
                onClick = onSubmit,
            )
        }

        OrHorizontalRule()

        // Sign In
        Column(
            modifier = Modifier
                .fillMaxWidth(),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.End,
        ) {
            HaveAnAccount(
                leftLevel = "Already have an account?",
                rightLevel = "Sign In",
                onSignInClick
            )
        }
    }
}

/*
@Composable
fun BottomComponent(navController: NavHostController) {
    Column {
        MyButton(labelVal = "Continue", navController = navController)
        Spacer(modifier = Modifier.height(10.dp))
        Row(
            verticalAlignment = Alignment.CenterVertically,
            modifier = Modifier.fillMaxWidth()
        ) {
            Divider(
                modifier = Modifier
                    .fillMaxWidth()
                    .weight(1f),
                thickness = 1.dp,
                color = Tertirary
            )
            Text(
                text = "OR",
                modifier = Modifier.padding(10.dp),
                color = Tertirary,
                fontSize = 20.sp
            )
            Divider(
                modifier = Modifier
                    .fillMaxWidth()
                    .weight(1f),
                thickness = 1.dp,
                color = Tertirary
            )
        }
        Spacer(modifier = Modifier.height(5.dp))
        Button(
            onClick = { */
/*TODO*//*
 },
            modifier = Modifier
                .fillMaxWidth(),
            colors = ButtonDefaults.outlinedButtonColors(
                containerColor = BgSocial
            )
        ) {
            Row(
                modifier = Modifier.fillMaxWidth(),
                verticalAlignment = Alignment.CenterVertically,
            ) {
                Image(
                    painter = painterResource(id = R.drawable.google),
                    contentDescription = "google icon"
                )
                Text(
                    text = "Login With Google",
                    fontSize = 18.sp,
                    color = Tertirary,
                    textAlign = TextAlign.Center,
                    modifier = Modifier.fillMaxWidth()
                )
            }
        }
    }
}*/
