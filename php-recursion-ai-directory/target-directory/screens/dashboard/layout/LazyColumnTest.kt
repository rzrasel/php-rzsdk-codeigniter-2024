package com.rzrasel.wordquiz.presentation.screens.dashboard.layout

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.IntrinsicSize
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.layout.wrapContentHeight
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.lazy.itemsIndexed
import androidx.compose.foundation.lazy.rememberLazyListState
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardModel

@Composable
fun LazyColumnTest(dashboardModel: DashboardModel) {
    val listState = rememberLazyListState()
    LazyColumn(
        modifier = Modifier
            .fillMaxWidth()
            .wrapContentHeight(),
        state = listState,
        contentPadding = PaddingValues(vertical = 10.dp),
        verticalArrangement = Arrangement.spacedBy(16.dp),
    ) {
        /*items(dashboardModel.taskCategory, key = { category -> category.categoryId }) { category ->
            category.categoryTitle?.let { categoryTitle ->
                Text(
                    text = categoryTitle,
                    modifier = Modifier
                        .padding(0.dp)
                        .fillMaxWidth(),
                    fontSize = 14.sp,
                    fontWeight = FontWeight.Bold,
                )
            }
            Column(
                modifier = Modifier
                    .wrapContentHeight()
            ) {
                category.taskItems.forEach { item ->
                    item.itemTitle?.let { itemTitle ->
                        Text(
                            text = itemTitle,
                            modifier = Modifier
                                .padding(0.dp)
                                .fillMaxWidth(),
                            fontSize = 14.sp,
                            fontWeight = FontWeight.Normal,
                        )
                    }
                }
            }
        }*/
    }
}

@Composable
fun LazyColumnTestOld4(dashboardModel: DashboardModel) {
    val listState = rememberLazyListState()
    val scrollState = rememberScrollState()
    Column(
        modifier = Modifier
            .fillMaxSize()
            .verticalScroll(rememberScrollState())
    ) {
        /*dashboardModel.taskCategory.forEach{ category ->
            category.categoryTitle?.let { categoryTitle ->
                Text(
                    text = categoryTitle,
                    modifier = Modifier
                        .padding(0.dp)
                        .fillMaxWidth(),
                    fontSize = 14.sp,
                    fontWeight = FontWeight.Bold,
                )
            }
            Column(
                modifier = Modifier
                    .wrapContentHeight()
            ) {
                category.taskItems.forEach { item ->
                    item.itemTitle?.let { itemTitle ->
                        Text(
                            text = itemTitle,
                            modifier = Modifier
                                .padding(0.dp)
                                .fillMaxWidth(),
                            fontSize = 14.sp,
                            fontWeight = FontWeight.Normal,
                        )
                    }
                }
            }
        }*/
    }
}

@Composable
fun LazyColumnTestOld3(dashboardModel: DashboardModel) {
    val listState = rememberLazyListState()
    val scrollState = rememberScrollState()
    Column(
        modifier = Modifier
            .fillMaxSize()
            .verticalScroll(rememberScrollState())
    ) {
        /*dashboardModel.taskCategory.forEach{ category ->
            category.categoryTitle?.let { categoryTitle ->
                Text(
                    text = categoryTitle,
                    modifier = Modifier
                        .padding(0.dp)
                        .fillMaxWidth(),
                    fontSize = 14.sp,
                    fontWeight = FontWeight.Bold,
                )
            }
            LazyColumn(
                modifier = Modifier
                    .fillMaxWidth()
                    .verticalScroll(scrollState)
                    .height(IntrinsicSize.Max),
                verticalArrangement = Arrangement.spacedBy(8.dp),
            ) {
                items(category.taskItems, key = { item -> item.itemId }) { item ->
                    item.itemTitle?.let { itemTitle ->
                        Text(
                            text = itemTitle,
                            modifier = Modifier
                                .padding(0.dp)
                                .fillMaxWidth(),
                            fontSize = 14.sp,
                            fontWeight = FontWeight.Normal,
                        )
                    }
                }
            }
        }*/
        /*repeat(1000) {
            Text(text = "Hello $it")
        }*/
    }
}

@Composable
fun LazyColumnTestOld2(dashboardModel: DashboardModel) {
    val listState = rememberLazyListState()
    LazyColumn(
        modifier = Modifier
            .width(600.dp),
        state = listState,
        contentPadding = PaddingValues(vertical = 10.dp),
        verticalArrangement = Arrangement.spacedBy(16.dp),
    ) {
        /*itemsIndexed(
            dashboardModel.taskCategory
        ) { index, category ->
            Column(
                modifier = Modifier
                    .fillMaxWidth(),
            ) {
                category.categoryTitle?.let { categoryTitle ->
                    Text(
                        text = categoryTitle,
                        modifier = Modifier
                            .padding(0.dp)
                            .fillMaxWidth(),
                        fontSize = 14.sp,
                        fontWeight = FontWeight.Bold,
                    )
                    LazyColumn(
                        verticalArrangement = Arrangement.spacedBy(8.dp)
                    ) {
                        itemsIndexed(category.taskItems) { index, item ->
                            item.itemTitle?.let { itemTitle ->
                                Text(
                                    text = itemTitle,
                                    modifier = Modifier
                                        .padding(0.dp)
                                        .fillMaxWidth(),
                                    fontSize = 14.sp,
                                    fontWeight = FontWeight.Normal,
                                )
                            }
                        }
                    }
                }
            }
        }*/
    }
}

@Composable
fun LazyColumnTestOld01(dashboardModel: DashboardModel) {
    val listState = rememberLazyListState()
    LazyColumn(
        modifier = Modifier.fillMaxWidth(),
        state = listState,
        contentPadding = PaddingValues(vertical = 10.dp),
        verticalArrangement = Arrangement.spacedBy(16.dp),
    ) {
        /*items(dashboardModel.taskCategory) { category ->
            category.categoryTitle?.let { categoryTitle ->
                Text(
                    text = categoryTitle,
                    modifier = Modifier
                        .padding(0.dp)
                        .fillMaxWidth(),
                    fontSize = 14.sp,
                    fontWeight = FontWeight.Bold,
                )
                LazyColumn(
                    modifier = Modifier.fillMaxWidth(),
                    verticalArrangement = Arrangement.spacedBy(8.dp),
                ) {
                    items(category.taskItems, key = { item -> item.itemId }) { item ->
                        item.itemTitle?.let { itemTitle ->
                            Text(
                                text = itemTitle,
                                modifier = Modifier
                                    .padding(0.dp)
                                    .fillMaxWidth(),
                                fontSize = 14.sp,
                                fontWeight = FontWeight.Normal,
                            )
                        }
                    }
                }
            }
        }*/
        /*//val dashboardModel: DashboardModel = (viewModel.uiState as DashboardUiState.Success).dashboardModel
        val dashboardModel: DashboardModel? = viewModel.dashboardModel.value
        if (dashboardModel != null) {
            items(dashboardModel.items) { item ->
                item.title?.let {
                    Text(
                        text = it,
                        modifier = Modifier
                            .padding(8.dp)       // Add padding around each item
                            .fillMaxWidth(),     // Make each item fill the available width
                        style = MaterialTheme.typography.bodyLarge
                    )
                }
            }
        }*/
    }
}

/*
// Iterate over the list of users
items(users, key = { user -> user.id }) { user ->
// Display each user using the UserItem composable
UserItem(user)
}*/
/*
https://www.droidcon.com/2023/01/23/nested-scroll-with-jetpack-compose/
https://stackoverflow.com/questions/75190976/how-to-achieve-multiple-header-and-body-elements-list-for-a-lazycolumn-in-compos
https://blog.kotlin-academy.com/nested-lazycolumn-in-jetpack-compose-79cc5d56c603
*/