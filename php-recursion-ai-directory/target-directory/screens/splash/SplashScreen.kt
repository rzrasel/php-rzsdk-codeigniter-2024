package com.rzrasel.wordquiz.presentation.screens.splash

import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.wrapContentHeight
import androidx.compose.foundation.layout.wrapContentWidth
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.material3.Text
import androidx.compose.ui.Alignment
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.unit.dp
import androidx.core.graphics.toColorInt
import androidx.lifecycle.viewmodel.compose.viewModel
import com.rzrasel.wordquiz.R
import com.rzrasel.wordquiz.navigation.NavigationRouteHelper
import com.rzrasel.wordquiz.presentation.components.layout.DefaultScaffold
import com.rzrasel.wordquiz.presentation.viewmodel.SplashViewModel
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun SplashScreen(
    viewModel: SplashViewModel = viewModel(),
    onNavigateToLoginScreen: () -> Unit,
) {
    /*LaunchedEffect(key1 = true) {
        delay(3000)
        navHostController.popBackStack()
        navHostController.navigate(NavigationRoute.LoginScreen)
    }*/
    /*val loginState by remember {
        splashViewModel.splashState
    }*/

    NavigationRouteHelper(
        shouldNavigate = { viewModel.splashState.isLoaded },
        destination = { onNavigateToLoginScreen() },
    )

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Color(0xFF172644)),
    ) {
        Column(
            modifier = Modifier
                .padding(horizontal = AppTheme.dimens.paddingLarge)
                .padding(bottom = AppTheme.dimens.paddingExtraLarge),
        ) {
            //Text(text = "Splash Screen")
            Box(
                modifier = Modifier
                    .fillMaxSize()
            ) {
                Column(
                    modifier = Modifier
                        .fillMaxSize(),
                    verticalArrangement = Arrangement.Center,
                    horizontalAlignment = Alignment.CenterHorizontally,
                ) {
                    Column(
                        modifier = Modifier
                            .wrapContentWidth()
                            .wrapContentHeight(),
                        verticalArrangement = Arrangement.Center,
                        horizontalAlignment = Alignment.CenterHorizontally,
                    ) {
                        Image(
                            painterResource(R.drawable.icon_quiz_logo),
                            contentDescription = "",
                            contentScale = ContentScale.FillBounds,
                            modifier = Modifier
                                .size(250.dp, 236.dp),
                        )
                    }
                }
            }
        }
    }

    /*DefaultScaffold {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .background(Color(0xFF172644)),
        ) {
            Column(
                modifier = Modifier
                    .padding(horizontal = AppTheme.dimens.paddingLarge)
                    .padding(bottom = AppTheme.dimens.paddingExtraLarge),
            ) {
                //Text(text = "Splash Screen")
                Box(
                    modifier = Modifier
                        .fillMaxSize()
                ) {
                    Column(
                        modifier = Modifier
                            .fillMaxSize(),
                        verticalArrangement = Arrangement.Center,
                        horizontalAlignment = Alignment.CenterHorizontally,
                    ) {
                        Column(
                            modifier = Modifier
                                .wrapContentWidth()
                                .wrapContentHeight(),
                            verticalArrangement = Arrangement.Center,
                            horizontalAlignment = Alignment.CenterHorizontally,
                        ) {
                            Image(
                                painterResource(R.drawable.icon_quiz_logo),
                                contentDescription = "",
                                contentScale = ContentScale.FillBounds,
                                modifier = Modifier
                                    .size(250.dp, 236.dp),
                            )
                        }
                    }
                }
            }
        }
    }*/
}
/*

https://github.com/Prashant-Chandel/jetpack-compose-Single-Activity-MVVM-clean-Architect-Example-with-KOIN-
https://github.com/SachinRupani/LoginWithJetpackCompose
https://github.com/MatthiasKerat/AuthModuleYT


*/