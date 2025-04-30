package com.rzrasel.wordquiz.presentation.screens.login

import android.app.Application
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.rz.logwriter.LogWriter
import com.rzrasel.wordquiz.navigation.NavigationRouteHelper
import com.rzrasel.wordquiz.presentation.components.dialog.LoadingDialog
import com.rzrasel.wordquiz.presentation.components.layout.DefaultScaffold
import com.rzrasel.wordquiz.presentation.screens.data.UserModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.UserLoginModule
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state.UserLoginUiState
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.UserLoginViewModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state.UserLoginUiEvent
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun AppLoginScreen(
    application: Application,
    onNavigateToRegistrationScreen: ()-> Unit,
    onNavigateToDashboardScreen: (UserModel)-> Unit,
) {
    //|------------------|CLASS VARIABLE SCOPE|------------------|
    val viewModel = remember {
        UserLoginModule.provideLoginViewModel(application.applicationContext)
    }
    //val uiState by viewModel.uiState.observeAsState()
    val uiState by remember { viewModel.uiState }
    var showLoadingDialog by remember { mutableStateOf(false) }

    //LogWriter.log("hi message")

    /*when(uiState) {
        is UserLoginUiState.Idle -> {
            LoginScreen(
                application = application,
                viewModel = viewModel,
                onNavigateToRegistrationScreen = onNavigateToRegistrationScreen,
            )
        }
        is UserLoginUiState.Loading -> {
            //CircularProgressIndicator()
            showLoadingDialog = true
        }
        is UserLoginUiState.Success -> {
            //Text("Welcome, ${(uiState as UserLoginUiState.Success).user.userAuthToken}")
            showLoadingDialog = false
        }
        is UserLoginUiState.Error -> {
            Text("Error: ${(uiState as UserLoginUiState.Error).message}")
        }
    }*/
    //Log.i("DEBUG_LOG", "UI state ${uiState.toString()}")

    //|---------------------|UI STATE CHECK|---------------------|
    when(uiState) {
        is UserLoginUiState.Success -> {
            showLoadingDialog = false
            //val userModel: UserModel = viewModel.userModel.value ?: UserModel("")
            val userModel: UserModel = UserModel("")
            userModel.userAuthToken = (uiState as UserLoginUiState.Success).user.data?.data?.accessToken
            //onNavigateToDashboardScreen(userModel)
            NavigationRouteHelper(
                shouldNavigate = { true },
                destination = {
                    onNavigateToDashboardScreen(userModel)
                },
            )
        }
        else -> {
            showLoadingDialog = when(uiState) {
                is UserLoginUiState.Loading -> {
                    true
                }
                else -> {
                    false
                }
            }
            LoginScreen(
                application = application,
                viewModel = viewModel,
                onNavigateToRegistrationScreen = onNavigateToRegistrationScreen,
                onNavigateToDashboardScreen = onNavigateToDashboardScreen,
            )
        }
    }

    //|------------------|SHOW LOADING DIALOG|-------------------|
    if(showLoadingDialog) {
        LoadingDialog("Please wait...") {
            showLoadingDialog = !showLoadingDialog
        }
    }
    /*LoginScreen(
        application = application,
        viewModel = viewModel,
        onNavigateToRegistrationScreen = onNavigateToRegistrationScreen,
    )*/
}

@Composable
fun LoginScreen(
    application: Application,
    viewModel: UserLoginViewModel = viewModel(),
    onNavigateToRegistrationScreen: ()-> Unit,
    onNavigateToDashboardScreen: (UserModel)-> Unit,
) {
    //|------------------|CLASS VARIABLE SCOPE|------------------|
    //viewModel.setApplication(application)
    //viewModel.userLogin()
    val loginState by remember {
        viewModel.loginState
    }
    val uiState by remember { viewModel.uiState }

    if(loginState.isLoginSuccessful) {
        return
    }

    DefaultLoginLayout {
        Text(
            text = "Sign In",
            fontSize = 24.sp,
            fontWeight = FontWeight.Bold,
        )
        LoginInputFields(
            loginState = loginState,
            onEmailOrMobileChange = { inputString ->
                viewModel.onUiEvent(
                    loginUiEvent = UserLoginUiEvent.EmailOrMobileChanged(inputString)
                )
            },
            onPasswordChange = { inputString ->
                viewModel.onUiEvent(
                    loginUiEvent = UserLoginUiEvent.PasswordChanged(inputString)
                )
            },
            onForgotPasswordClick = {},
            onSubmit = {
                viewModel.onUiEvent(loginUiEvent = UserLoginUiEvent.Submit)
            },
            onSignUpClick = {
                onNavigateToRegistrationScreen()
            },
            onSkipNowClick = {
                val userModel: UserModel = UserModel("")
                //userModel.userAuthToken = (uiState as UserLoginUiState.Success).user.userAuthToken
                //onNavigateToDashboardScreen(userModel)
                //onNavigateToDashboardScreen(userModel)
                /*NavigationRouteHelper(
                    shouldNavigate = { true },
                    destination = {
                        onNavigateToDashboardScreen(userModel)
                    },
                )*/
                viewModel.onCallSkipped()
            },
        )
    }
}

@Composable
fun DefaultLoginLayout(
    modifier: Modifier = Modifier,
    content: @Composable ()-> Unit,
) {
    DefaultScaffold {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(horizontal = AppTheme.dimens.paddingLarge)
                .padding(bottom = AppTheme.dimens.paddingExtraLarge),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.CenterHorizontally,
        ) {
            Column(
                modifier = Modifier
                    .fillMaxWidth(),
                verticalArrangement = Arrangement.Top,
                horizontalAlignment = Alignment.Start,
            ) {
                content()
            }
        }
    }
}

@Composable
fun OnNavigateToDashboard(
    userModel: UserModel,
    onNavigateToDashboardScreen: (UserModel)-> Unit,
) {
    val tempUserModel: UserModel = UserModel("")
    tempUserModel.userAuthToken = userModel.userAuthToken
    NavigationRouteHelper(
        shouldNavigate = { true },
        destination = {
            onNavigateToDashboardScreen(tempUserModel)
        },
    )
}

@Preview(showBackground = true)
@Composable
fun PreviewLoginScreen() {
    /*ComposeLoginTheme {
        LoginScreen(
            onNavigateToForgotPassword = {},
            onNavigateToRegistration = {},
            onNavigateToAuthenticatedRoute = {}
        )
    }*/
    //LoginScreen()
    /*LoginScreen(
        onNavigateToForgotPassword = {},
        onNavigateToRegistration = {},
        onNavigateToAuthenticatedRoute = {}
    )*/
}

/*

https://github.com/SachinRupani/LoginWithJetpackCompose/tree/main

*/