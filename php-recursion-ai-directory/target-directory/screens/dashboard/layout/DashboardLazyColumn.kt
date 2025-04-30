package com.rzrasel.wordquiz.presentation.screens.dashboard.layout

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.wrapContentHeight
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.lazy.rememberLazyListState
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.rzrasel.wordquiz.core.enumtype.CategoryType
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardCategoryModel
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardModel
import com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data.DashboardTaskItemModel
import com.rzrasel.wordquiz.presentation.screens.data.UserModel
import com.rzrasel.wordquiz.presentation.screens.data.UserTaskItemModel

@Composable
fun DashboardLazyColumn(
    userModel: UserModel,
    dashboardModel: DashboardModel,
    onClickTaskItem: (DashboardCategoryModel, DashboardTaskItemModel, UserTaskItemModel)-> Unit,
) {
    val listState = rememberLazyListState()
    LazyColumn(
        modifier = Modifier
            .fillMaxWidth()
            .wrapContentHeight(),
        state = listState,
        contentPadding = PaddingValues(vertical = 10.dp),
        verticalArrangement = Arrangement.spacedBy(16.dp),
    ) {
        dashboardModel.data?.data?.forEach{ quizItem ->
            items(quizItem.categories, key = { category -> category.categoryId!! }) { category ->
                val categoryType: CategoryType = CategoryType.find(category.categoryType)
                val categoryTitle = category.categoryTitle ?: ""
                if (categoryType == CategoryType.DEFAULT) {
                    DashboardDefaultHeaderLayout(categoryTitle)
                } else {
                    DashboardTaskCategoryHeaderLayout(categoryTitle)
                }
                Column(
                    modifier = Modifier
                        .wrapContentHeight(),
                    verticalArrangement = Arrangement.spacedBy(8.dp),
                ) {
                    category.taskItems.forEach { item ->
                        val itemTitle = item.itemTitle ?: ""
                        val itemSubtitle = item.itemSubtitle ?: ""
                        val itemButtonLabel = item.itemButtonLabel ?: ""
                        if(categoryType == CategoryType.LIVE_QUIZ || categoryType == CategoryType.REGULAR_QUIZ || categoryType == CategoryType.PRACTICE || categoryType == CategoryType.READING) {
                            category.categoryId?.let {
                                item.itemId?.let { it1 ->
                                    TaskRegularItemBodyLayout(
                                        taskCategoryModel = category,
                                        taskItemModel = item,
                                        userModel = userModel,
                                        categoryId = it,
                                        itemId = it1,
                                        itemTitle,
                                        itemSubtitle,
                                        itemButtonLabel,
                                        onClickTaskItem = onClickTaskItem,
                                    )
                                }
                            }
                        }
                    }
                }
            }
        }
        /*items(dashboardModel.taskCategory, key = { category -> category.categoryId }) { category ->
            val categoryType: CategoryType = CategoryType.find(category.categoryType)
            val categoryTitle = category.categoryTitle ?: ""
            if (categoryType == CategoryType.DEFAULT) {
                DashboardDefaultHeaderLayout(categoryTitle)
            } else {
                DashboardTaskCategoryHeaderLayout(categoryTitle)
            }
            Column(
                modifier = Modifier
                    .wrapContentHeight(),
                verticalArrangement = Arrangement.spacedBy(8.dp),
            ) {
                category.taskItems.forEach { item ->
                    val itemTitle = item.itemTitle ?: ""
                    val itemSubtitle = item.itemSubtitle ?: ""
                    val itemButtonLabel = item.itemButtonLabel ?: ""
                    if(categoryType == CategoryType.LIVE_QUIZ || categoryType == CategoryType.REGULAR_QUIZ || categoryType == CategoryType.PRACTICE || categoryType == CategoryType.READING) {
                        TaskRegularItemBodyLayout(
                            taskCategoryModel = category,
                            taskItemModel = item,
                            userModel = userModel,
                            categoryId = category.categoryId,
                            itemId = item.itemId,
                            itemTitle,
                            itemSubtitle,
                            itemButtonLabel,
                            onClickTaskItem = onClickTaskItem,
                        )
                    }
                }
            }
        }*/
    }
}

@Composable
fun TaskItemBodyLayout(title: String = "") {
    Text(
        text = title,
        modifier = Modifier
            .padding(0.dp)
            .fillMaxWidth(),
        fontSize = 14.sp,
        fontWeight = FontWeight.Normal,
    )
}

@Composable
fun DashboardLazyColumnOld1(dashboardLayoutData: ArrayList<DashboardLayoutData<*>>) {
    val listState = rememberLazyListState()
    LazyColumn(
        modifier = Modifier
            .fillMaxWidth()
            .wrapContentHeight(),
        state = listState,
        contentPadding = PaddingValues(vertical = 10.dp),
        verticalArrangement = Arrangement.spacedBy(16.dp),
    ) {
        items(
            dashboardLayoutData,
            key = { category -> category.id },
        ) { category ->
            category.header
            /*Column(
                modifier = Modifier
                    .wrapContentHeight()
            ) {
                if (category.body.isNotEmpty()) {
                    category.body.forEach {
                        it
                    }
                }
            }*/
        }
    }
}

data class DashboardLayoutData<T>(
    val id: String,
    val header: T,
    val body: ArrayList<T> = arrayListOf(),
)

@Composable
fun DashboardLazyColumnOld1(dashboardModel: DashboardModel) {
    val dashboardLayout: ArrayList<DashboardLayoutData<*>> = ArrayList()
    var layout: DashboardLayoutData<*> = DashboardLayoutData(
        id = "default_header_layout",
        header = DashboardDefaultHeaderLayout(),
        body = arrayListOf()
    )
    dashboardLayout.add(layout)
    dashboardLayout.forEach { item ->
        item.header
    }
}