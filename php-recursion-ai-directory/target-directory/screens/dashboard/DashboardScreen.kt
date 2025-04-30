package com.rzrasel.wordquiz.presentation.screens.dashboard

import android.app.Application
import android.content.Context
import android.util.Log
import android.widget.Toast
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
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.rz.logwriter.DebugLog
import com.rzrasel.wordquiz.core.enumtype.AccessMode
import com.rzrasel.wordquiz.core.enumtype.CategoryType
import com.rzrasel.wordquiz.navigation.NavigationRouteHelper
import com.rzrasel.wordquiz.presentation.components.dialog.LoadingDialog
import com.rzrasel.wordquiz.presentation.components.layout.DefaultScaffold
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.DashboardModule
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.DashboardViewModel
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardModel
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.state.DashboardNavigateScreenState
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.state.DashboardUiState
import com.rzrasel.wordquiz.presentation.screens.dashboard.layout.DashboardDefaultHeaderLayout
import com.rzrasel.wordquiz.presentation.screens.dashboard.layout.DashboardLayoutData
import com.rzrasel.wordquiz.presentation.screens.dashboard.layout.DashboardLazyColumn
import com.rzrasel.wordquiz.presentation.screens.data.UserModel
import com.rzrasel.wordquiz.presentation.screens.data.UserTaskItemModel
import com.rzrasel.wordquiz.ui.theme.AppTheme

@Composable
fun AppDashboardScreen(
    application: Application,
    userModel: UserModel = UserModel(""),
    onNavigateToFlatReading: (UserTaskItemModel)-> Unit,
    onNavigateToTaskItem: (UserTaskItemModel)-> Unit,
) {
    //|------------------|CLASS VARIABLE SCOPE|------------------|
    val context = LocalContext.current
    var showCustomDialog by remember { mutableStateOf(false) }
    val viewModel = remember {
        DashboardModule.provideDashboardViewModel(application.applicationContext, userModel.userAuthToken ?: "")
    }
    val uiState by remember { viewModel.uiState }
    var showLoadingDialog by remember { mutableStateOf(false) }
    val dashboardModel: DashboardModel? = viewModel.dashboardModel.value
    //
    //Log.i("DEBUG_LOG", "User data ${userModel.toString()}")
    //viewModel.getDashboard(userAuthToken = userModel.userAuthToken)

    //|---------------------|UI STATE CHECK|---------------------|
    when(uiState) {
        is DashboardUiState.Idle -> {
            viewModel.getDashboard(userAuthToken = userModel.userAuthToken)
        }
        is DashboardUiState.Success -> {
            showLoadingDialog = false
            val accessToken = dashboardModel?.data?.accessToken
            userModel.userAuthToken = accessToken
            //DebugLog.Log("Success data ${dashboardModel.toString()}")
            DashboardScreen(
                application = application,
                viewModel= viewModel,
                userModel = userModel,
                onNavigateToFlatReading = onNavigateToFlatReading,
                onNavigateToTaskItem = onNavigateToTaskItem,
            )
        }
        else -> {
            //viewModel.getDashboard(userAuthToken = userModel.userAuthToken)
            showLoadingDialog = when(uiState) {
                is DashboardUiState.Loading -> {
                    true
                }
                is DashboardUiState.Error -> {
                    val message: String = (uiState as DashboardUiState.Error).message
                    Toast.makeText(context, message, Toast.LENGTH_LONG).show()
                    false
                }
                else -> {
                    false
                }
            }
        }
    }
    /*DashboardScreen(
        application = application,
        viewModel= viewModel,
    )*/

    //|------------------|SHOW LOADING DIALOG|-------------------|
    if(showLoadingDialog) {
        LoadingDialog("Please wait...") {
            showLoadingDialog = !showLoadingDialog
        }
    }
}

@Composable
fun DashboardScreen(
    application: Application,
    viewModel: DashboardViewModel = viewModel(),
    userModel: UserModel = UserModel(""),
    onNavigateToFlatReading: (UserTaskItemModel)-> Unit,
    onNavigateToTaskItem: (UserTaskItemModel)-> Unit,
) {
    val context: Context = LocalContext.current
    var clickedUserTaskItem by remember { mutableStateOf(
        UserTaskItemModel(
            userAuthToken = userModel.userAuthToken ?: "",
            categoryId = "",
            categoryType = "",
            itemId = "",
            accessMode = "",
        )
    ) }
    var dashboardScreenState by remember { mutableStateOf<DashboardNavigateScreenState>(DashboardNavigateScreenState.Idle) }
    //val catType: CategoryType = CategoryType.find("regular_quiz")
    //Log.i("DEBUG_LOG", "Category type ${catType.toString()}")
    when(dashboardScreenState) {
        is DashboardNavigateScreenState.FlatReading -> {
            NavigationRouteHelper(
                shouldNavigate = { true },
                destination = {
                    onNavigateToFlatReading(clickedUserTaskItem)
                }
            )
            dashboardScreenState = DashboardNavigateScreenState.Idle
        }
        is DashboardNavigateScreenState.FlatPractice -> {
            NavigationRouteHelper(
                shouldNavigate = { true },
                destination = {
                    //onNavigateToFlatReading(clickedUserTaskItem)
                    onNavigateToTaskItem(clickedUserTaskItem)
                }
            )
            dashboardScreenState = DashboardNavigateScreenState.Idle
        } else -> {
            dashboardScreenState = DashboardNavigateScreenState.Idle
        }
    }
    DefaultScaffold {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(horizontal = AppTheme.dimens.paddingLarge)
                .padding(bottom = AppTheme.dimens.paddingExtraLarge)
        ) {
            //Text(text = "Dashboard Screen")
            //DashboardDefaultHeaderLayout()
            //
            val dashboardModel: DashboardModel? = viewModel.dashboardModel.value
            if(dashboardModel != null) {
                //DebugLog.Log("dashboardModel ${dashboardModel.toString()}")
                DashboardLazyColumn(
                    userModel, dashboardModel,
                    onClickTaskItem = { taskCategoryModel, taskItemModel, userTaskItem ->
                        val accessMode: AccessMode = AccessMode.find(taskItemModel.accessMode)
                        val categoryType: CategoryType = CategoryType.find(taskCategoryModel.categoryType)
                        clickedUserTaskItem = userTaskItem
                        //Toast.makeText(context, "Toast ${clickedUserTaskItem.itemId}", Toast.LENGTH_LONG).show()
                        //onNavigateToFlatReading(clickedUserTaskItem)
                        //dashboardScreenState = DashboardNavigateScreenState.FlatReading(true)
                        when(categoryType) {
                            CategoryType.READING -> {
                                dashboardScreenState = DashboardNavigateScreenState.FlatReading(true)
                            } CategoryType.PRACTICE -> {
                                dashboardScreenState = DashboardNavigateScreenState.FlatPractice(true)
                            } else -> {
                                Toast.makeText(context, "Coming soon", Toast.LENGTH_LONG).show()
                                dashboardScreenState = DashboardNavigateScreenState.FlatReading(true)
                            }
                        }
                    }
                )
            }
            /*if(dashboardModel != null) {
                //LazyColumnTest(dashboardModel)
                //DashboardLazyColumn(dashboardModel)
                DashboardLazyColumn(
                    userModel, dashboardModel,
                    onClickTaskItem = { taskCategoryModel, taskItemModel, userTaskItem ->
                        val accessMode: AccessMode = AccessMode.find(taskItemModel.accessMode)
                        val categoryType: CategoryType = CategoryType.find(taskCategoryModel.categoryType)
                        clickedUserTaskItem = userTaskItem
                        //Toast.makeText(context, "Toast ${clickedUserTaskItem.itemId}", Toast.LENGTH_LONG).show()
                        //onNavigateToFlatReading(clickedUserTaskItem)
                        //dashboardScreenState = DashboardNavigateScreenState.FlatReading(true)
                        when(categoryType) {
                            CategoryType.READING -> {
                                dashboardScreenState = DashboardNavigateScreenState.FlatReading(true)
                            } CategoryType.PRACTICE -> {
                                dashboardScreenState = DashboardNavigateScreenState.FlatPractice(true)
                            } else -> {
                                Toast.makeText(context, "Coming soon", Toast.LENGTH_LONG).show()
                            }
                        }
                    }
                )
            }*/
        }
    }
}