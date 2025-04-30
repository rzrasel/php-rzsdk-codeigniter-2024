package com.rzrasel.wordquiz.presentation.screens.registration

import android.app.Application
import android.widget.Toast
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.lifecycle.viewmodel.compose.viewModel
import com.rzrasel.wordquiz.presentation.components.dialog.LoadingDialog
import com.rzrasel.wordquiz.presentation.components.layout.DefaultScaffold
import com.rzrasel.wordquiz.presentation.screens.state.RegistrationUiEvent
import com.rzrasel.wordquiz.presentation.screens.state.UserRegistrationUiState
import com.rzrasel.wordquiz.presentation.viewmodel.RegistrationViewModel
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun AppRegistrationScreen(
    application: Application,
    viewModel: RegistrationViewModel = viewModel(),
    onNavigateToLoginScreen: () -> Unit,
) {
    val context = LocalContext.current
    val uiState by viewModel.uiState.collectAsStateWithLifecycle()
    val registrationModel = viewModel.registrationModel.value
    var showLoading by remember { mutableStateOf(false) }

    // Ensure application is set only once
    LaunchedEffect(Unit) {
        viewModel.setApplication(application)
    }

    // Handle loading state
    LaunchedEffect(uiState) {
        showLoading = uiState is UserRegistrationUiState.Loading
    }

    // Handle success state
    if(uiState is UserRegistrationUiState.Success) {
        val message = registrationModel?.message.orEmpty()
        Toast.makeText(context, message, Toast.LENGTH_LONG).show()
        viewModel.onUpdateUiState(UserRegistrationUiState.Idle)
    }

    // Handle error state
    if(uiState is UserRegistrationUiState.Error) {
        Toast.makeText(context, (uiState as UserRegistrationUiState.Error).message, Toast.LENGTH_LONG).show()
        //Toast.makeText(context, "Error", Toast.LENGTH_LONG).show()
    }

    // Display the RegistrationScreen
    RegistrationScreen(
        application = application,
        viewModel = viewModel,
        onNavigateToLoginScreen = onNavigateToLoginScreen
    )

    // Show loading dialog
    if(showLoading) {
        LoadingDialog("Please wait...") { showLoading = false }
    }
}

/*@Composable
fun AppRegistrationScreen(
    application: Application,
    viewModel: RegistrationViewModel = viewModel(),
    onNavigateToLoginScreen: () -> Unit,
) {
    // Context and UI state
    val context = LocalContext.current
    val uiState by viewModel.uiState.collectAsStateWithLifecycle()
    //val uiState by remember { viewModel.uiState }
    //val uiState by viewModel.uiState.collectAsState()
    val registrationModel = viewModel.registrationModel.value

    // Ensure application is set once
    LaunchedEffect(Unit) {
        viewModel.setApplication(application)
    }

    // Handle success state
    if(uiState is UserRegistrationUiState.Success) {
        val message = registrationModel?.message.orEmpty()
        Toast.makeText(context, message, Toast.LENGTH_LONG).show()
        viewModel.onUpdateUiState(UserRegistrationUiState.Idle)
    } else {
        RegistrationScreen(
            application = application,
            viewModel = viewModel,
            onNavigateToLoginScreen = onNavigateToLoginScreen,
        )
    }

    // Show loading dialog if in Loading state
    if(uiState is UserRegistrationUiState.Loading) {
        LoadingDialog("Please wait...") { *//* Do nothing *//* }
    }
}*/

@Composable
fun RegistrationScreen(
    application: Application,
    viewModel: RegistrationViewModel = viewModel(),
    onNavigateToLoginScreen: ()-> Unit,
) {
    //|------------------|CLASS VARIABLE SCOPE|------------------|
    viewModel.setApplication(application)
    //viewModel.userRegistration()
    val registrationState by remember {
        viewModel.registrationState
    }

    DefaultRegistrationLayout {
        Text(
            text = "Sign Up",
            fontSize = 24.sp,
            fontWeight = FontWeight.Bold,
        )
        RegistrationInputFields(
            registrationState = registrationState,
            onEmailAddressChange = { inputString ->
                viewModel.onUiEvent(
                    registrationUiEvent = RegistrationUiEvent.EmailChanged(inputString)
                )
            },
            onPasswordChange = { inputString ->
                viewModel.onUiEvent(
                    registrationUiEvent = RegistrationUiEvent.PasswordChanged(inputString)
                )
            },
            onConfirmPasswordChange = { inputString ->
                viewModel.onUiEvent(
                    registrationUiEvent = RegistrationUiEvent.ConfirmPasswordChanged(
                        inputValue = inputString
                    )
                )
            },
            onSubmit = {
                viewModel.onUiEvent(registrationUiEvent = RegistrationUiEvent.Submit)
            },
            onSignInClick = {
                onNavigateToLoginScreen()
            },
        )
    }
}

@Composable
fun DefaultRegistrationLayout(
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